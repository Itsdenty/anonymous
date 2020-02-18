<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Carbon;
use App\Campaign;
use App\Smtp;
use App\MailApi;
use App\FromReplyEmail;
use App\Traits\SendMailTrait;
use App\Contact;
use App\Link;
use App\Activity;
use App\Http\Controllers\DetectController;

class CampaignController extends Controller
{
    use SendMailTrait;

    //
    public function viewCampaigns()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $num_of_contacts = $user->currentAccount->contacts->count();

        $num_of_opens = $user->currentAccount->campaigns->map(function($campaign){
            return $campaign->contacts()->wherePivot('opened', true)->get();
        })->flatten()->unique('id')->count();

        $num_of_clicks = $user->currentAccount->campaigns->map(function($campaign){
            return $campaign->links->map(function($link){
                return $link->contacts;
            });
        })->flatten()->unique('id')->count();

        $num_of_unsubscribers = $user->currentAccount->campaigns->map(function($campaign){
            return $campaign->contacts()->wherePivot('unsubscribed', true)->get();
        })->flatten()->unique('id')->count();


        // $campaigns = $user->currentAccount->campaigns->sortByDesc('id');
        $campaigns = Campaign::where('sub_account_id', $user->currentAccount->id)->orderBy('id', 'desc')->paginate(10);

        // return view('campaigns.campaigns')->with(['user' => $user, 'main_user' => $mainUser, 'campaigns' => $campaigns, 'num_of_contacts' => $num_of_contacts, 'num_of_opens' => $num_of_opens, 'num_of_clicks' => $num_of_clicks, 'num_of_unsubscribers' => $num_of_unsubscribers]);
        return view('campaigns.campaigns')->with(['user' => $user, 'main_user' => $mainUser, 'campaigns' => $campaigns, 'num_of_contacts' => $num_of_contacts, 'num_of_opens' => $num_of_opens, 'num_of_clicks' => $num_of_clicks, 'num_of_unsubscribers' => $num_of_unsubscribers]);

    }

    public function createCampaign()
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $from_reply_emails = $user->currentAccount->fromReplyEmails->where('confirmed', true);
        $smtps = $user->currentAccount->smtps;
        $mail_apis = $user->currentAccount->mailApis;
        // $campaign = Campaign

        if($from_reply_emails->isEmpty())
        {
            return redirect('from_reply')->with('error', "Please Add & Verify From/Reply Email(s) to create campaign(s)");
        }

        if($smtps->isEmpty() && $mail_apis->isEmpty())
        {
            return redirect('integration')->with('status', "Add Integration(s) to create campaign(s)");
        }

        $campaign = new Campaign();
        $campaign->sub_account_id = $user->currentAccount->id;
        $campaign->title = "New Campaign";
        $campaign->save();

        return redirect('create/'.$campaign->id.'/campaign')->with(['campaign' => $campaign]);
    }

    public function viewCampaign($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $campaign = Campaign::find($id);
        if($campaign)
        {
            if($campaign->sub_account_id == $user->currentAccount->id)
            {
                $from_reply_emails = $user->currentAccount->fromReplyEmails->where('confirmed', true);
                $smtps = $user->currentAccount->smtps;
                $mail_apis = $user->currentAccount->mailApis;

                $contacts = $user->currentAccount->contacts->where('unsubscribed', false);

                $campaign->contacts()->detach();
                foreach($contacts as $contact) {
                    $campaign->contacts()->attach($contact->id);
                }

                return view('campaigns.create')->with(['user' => $user, 'main_user' => $mainUser, 'from_reply_emails' => $from_reply_emails, 'smtps' => $smtps, 'mail_apis' => $mail_apis, 'campaign' => $campaign, 'contacts' => $contacts]);
            }
            return redirect('campaigns')->with('error', 'Unauthorized access to Campaign');
        }
        return redirect('campaigns')->with('error', 'Campaign not found');
    }

    public function editCampaign($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $campaign = Campaign::find($id);
        if($campaign)
        {
            if($campaign->status  != 'draft' && $campaign->status != 'later')
            {
                return redirect('campaigns')->with('error', 'Cannot Edit Campaign already Sent or in Progress');
            }

            if($campaign->sub_account_id == $user->currentAccount->id)
            {
                $from_reply_emails = $user->currentAccount->fromReplyEmails->where('confirmed', true);
                $smtps = $user->currentAccount->smtps;
                $mail_apis = $user->currentAccount->mailApis;

                $contacts = $campaign->contacts;

                return view('campaigns.edit')->with(['user' => $user, 'main_user' => $mainUser, 'from_reply_emails' => $from_reply_emails, 'smtps' => $smtps, 'mail_apis' => $mail_apis, 'campaign' => $campaign, 'contacts' => $contacts]);
            }
            return redirect('campaigns')->with('error', 'Unauthorized access to Campaign');
        }
        return redirect('campaigns')->with('error', 'Campaign not found');
    }


    public function store(Campaign $campaign, Request $request)
    {
        $campaign->contacts()->detach();
        if($request->campaign_contacts){
            foreach($request->campaign_contacts as $ct) {
                $campaign->contacts()->attach($ct['id']);
            }

        }

        if($request->filter_query_string)
        {
            $campaign->filter_query = $request->filter_query_string;
            $campaign->save();
        }
    }

    public function updateCampaign(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();

        $campaign = Campaign::find($data['campaign_id']);
        if(!$campaign)
        {
            $campaign = new Campaign();
        }

        if($campaign->status  != 'draft' && $campaign->status != 'later')
        {
            return redirect('campaigns')->with('error', 'Cannot Edit Campaign already Sent or in Progress');
        }

        $campaign->title = $data['title'];
        $campaign->subject_a = $data['subject_a'];
        if(isset($data['ab_test']))
        {
            $campaign->ab_test = 1;
            $campaign->subject_b = $data['subject_b'];
        }
        $campaign->from_reply_id = $data['from_reply_id'];

        $integration = explode('_', $data['integration']);
        if($integration[0] == 'smtp')
        {
            $campaign->smtp_id = $integration[1];
            $campaign->mail_api_id = null;
        }
        else
        {
            $campaign->mail_api_id = $integration[1];
            $campaign->smtp_id = null;
        }

        if(isset($data['send_later']) && $data['send_later'] == 1)
        {
            $campaign->status = 'later';
            $campaign->send_date = $data['schedule_date'];
            $campaign->actual_send_date = date('Y-m-d H:i', (strtotime($data['schedule_date']) - ($user->profile->timezone->offset * 60 * 60)));

            $message = "Campaign will be sent at ". $data['schedule_date'];
        }
        else
        {
            $campaign->status = 'sent';
            $campaign->actual_send_date = \Carbon\Carbon::now();
            $message = "Campaign Sent" ;
        }
        $campaign->save();

        return redirect('campaigns')->with('status', $message);
    }

    public function sendNow($campaign)
    {
        if($campaign->smtp_id != null)
        {
            $this->smtpSendMail($campaign);
        }
        else if($campaign->mail_api_id != null)
        {
            $mailApi = MailApi::find($campaign->mail_api_id);
            if($mailApi && $mailApi->api_channel_id == 2)
            {
                $this->mailgunSendMail($campaign);
            }
            else if($mailApi && $mailApi->api_channel_id == 1)
            {
                $this->sendgridSendMail($campaign);
            }
        }
    }

    public function trackEmail($campaign_id, $contact_id)
    {
        $campaign = Campaign::find($campaign_id);
        $contact = Contact::find($contact_id);

        $vdata = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));
        $vbrowser = explode(' ',DetectController::browser());

        // $domain_name = \Request::server('HTTP_REFERER') ? \Request::server('HTTP_REFERER') : "Others";
        $device = DetectController::isMobile() ? 'Mobile' : 'Desktop';
        $browser = $vbrowser[0];
        $country_name = $vdata['geoplugin_countryName'];

        if($campaign && $contact)
        {
            $contact_details = $campaign->contacts->find($contact->id);
            $domain_name = explode('@', $contact->email)[1];
            $count = $contact_details->pivot->open_count;
            $campaign->contacts()->updateExistingPivot($contact_id, ['opened' => true, 'open_count' => $count + 1, 'device' => $device, 'browser' => $browser, 'domain' => $domain_name, 'country_name' => $country_name]);
            Activity::create(['content' => 'Opened email "'. $campaign->title .'"', 'category' => 'opens', 'contact_id' => $contact->id]);

            $contact->country_name = $country_name;
            $contact->save();
        }
    }

    public function trackLink($replacement_url, $contact_id)
    {
        $link = Link::where('replacement_url', $replacement_url)->first();
        $contact = Contact::find($contact_id);

        $vdata = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));
        $vbrowser = explode(' ',DetectController::browser());

        // $domain_name = \Request::server('HTTP_REFERER');
        $device = DetectController::isMobile() ? 'Mobile' : 'Desktop';
        $browser = $vbrowser[0];
        $country_name = $vdata['geoplugin_countryName'];

        if($link && $contact)
        {
            if(!$link->contacts()->where('id', $contact_id)->exists())
            {
                $link->contacts()->attach($contact_id);
            }
            $domain_name = explode('@', $contact->email)[1];

            $contact_details = $link->contacts->find($contact->id);
            $count = $contact_details->pivot->click_count;
            $link->contacts()->updateExistingPivot($contact_id, ['click_count' => $count + 1, 'device' => $device, 'browser' => $browser, 'domain' => $domain_name, 'country_name' => $country_name]);
            Activity::create(['content' => 'Click link <a href="'. $link->actual_url .'">'. $link->actual_url .'</a>', 'category' => 'clicks', 'contact_id' => $contact->id]);

            $contact->country_name = $country_name;
            $contact->save();

            return Redirect::to($link->actual_url);
        }

        return "Link not found";
    }

    public function saveCampaign(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();

        $campaign = Campaign::find($data['campaign_id']);
        if(!$campaign)
        {
            $campaign = new Campaign();
        }

        if($campaign->status  != 'draft' && $campaign->status != 'later')
        {
            return redirect('campaigns')->with('error', 'Cannot Edit Campaign already Sent or in Progress');
        }

        $campaign->title = $data['title'];
        $campaign->subject_a = $data['subject_a'];
        if(isset($data['ab_test']))
        {
            $campaign->ab_test = 1;
            $campaign->subject_b = $data['subject_b'];
        }
        $campaign->from_reply_id = $data['from_reply_id'];

        $integration = explode('_', $data['integration']);
        if($integration[0] == 'smtp')
        {
            $campaign->smtp_id = $integration[1];
            $campaign->mail_api_id = null;
        }
        else
        {
            $campaign->mail_api_id = $integration[1];
            $campaign->smtp_id = null;
        }

        $campaign->status = 'draft';
        $campaign->save();

        return $campaign;
    }

    public function saveRichContent(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();

        $campaign = Campaign::find($data['campaign_id']);
        if($campaign)
        {
            if($campaign->sub_account_id == $user->currentAccount->id)
            {
                $campaign->content = $data['content'];
                $campaign->save();
            }
        }
    }

    public function duplicateCampaign($campaign_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $campaign = Campaign::find($campaign_id);
        if($campaign)
        {
            if($campaign->sub_account_id == $user->currentAccount->id)
            {
                $dup_campaign = new Campaign();
                $dup_campaign->title = $campaign->title.' (duplicate)';
                $dup_campaign->subject_a = $campaign->subject_a;
                $dup_campaign->subject_b = $campaign->subject_b;
                $dup_campaign->from_reply_id = $campaign->from_reply_id;
                $dup_campaign->smtp_id = $campaign->smtp_id;
                $dup_campaign->mail_api_id = $campaign->mail_api_id;
                $dup_campaign->content = $campaign->content;
                $dup_campaign->ab_test = $campaign->ab_test;
                $dup_campaign->status = 'draft';
                $dup_campaign->sub_account_id = $campaign->sub_account_id;
                $dup_campaign->save();

                return redirect('campaigns')->with('status', "Campaign Duplicated Successfully");
            }
            return redirect('campaigns')->with('error', "Unauthorized Access");
        }
        return redirect('campaigns')->with('error', 'Campaign not found');
    }

    public function sendToSender(Request $request)
    {
        $campaign = $this->saveCampaign($request);

        $this->sendSender($campaign);
        return;
    }

    public function deleteCampaign($campaign_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $campaign = Campaign::find($campaign_id);
        if($campaign)
        {
            if($campaign->sub_account_id == $user->currentAccount->id)
            {
                if($campaign->status != 'progress')
                {
                    $campaign->delete();
                    return back()->with('status', "Campaign Deleted Successfully");
                }
                return back()->with('error', "Can't delete a Campaign in Progress");
            }
            return back()->with('error', "Unathorized Access");
        }
        return back()->with('error', 'Campaign not found');
    }

    public function removeCampaignContact($contact_id, $campaign_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $campaign = Campaign::find($campaign_id);
        if($campaign)
        {
            if($campaign->sub_account_id == $user->currentAccount->id)
            {
                $campaign->contacts()->detach($contact_id);
                return response()->json(['campaign_contacts_count' => $campaign->contacts->count()], 200);
            }
        }
        return response()->json(['error' => "An error occured"], 404);
    }

    public function viewAnalysis($campaign_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        if($mainUser->member)
        {
            if($mainUser->member->role == 2)
            {
                return back()->with('error', 'This page can only be accessed by owners');
            }
        }

        $campaign = Campaign::find($campaign_id);
        if($campaign)
        {
            if($campaign->sub_account_id == $user->currentAccount->id)
            {
                $total_open_time_difference = 0;
                $average_time_to_open = 0;
                $open_contacts = $campaign->contacts()->wherePivot('opened', true)->get();
                $open_contacts_count = $campaign->contacts()->wherePivot('opened', true)->count();
                foreach($open_contacts as $con)
                {
                    $total_open_time_difference += strtotime($con->pivot->updated_at) - strtotime($campaign->actual_send_date);
                }
                if($open_contacts_count != 0)
                {
                    $average_time_to_open = intdiv($total_open_time_difference, $open_contacts_count);
                }

                $last_opened_contact = $campaign->contacts()->wherePivot('opened', true)->orderBy('pivot_updated_at', 'desc')->first();
                $last_open_time = "";
                if($last_opened_contact)
                {
                    $last_open_time = date('M j, Y H:i:s', (strtotime($last_opened_contact->pivot->updated_at) + ($user->profile->timezone->offset * 60 * 60)));
                }

                $total_click_time_difference = 0;
                $average_time_to_click = 0;
                $click_contacts = $campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id');
                $click_contacts_count = $campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->count();
                foreach($click_contacts as $con)
                {
                    $total_click_time_difference += strtotime($con->pivot->created_at) - strtotime($campaign->actual_send_date);
                }
                if($click_contacts_count != 0)
                {
                    $average_time_to_click = intdiv($total_click_time_difference, $click_contacts_count);
                }

                $last_click_contact = $campaign->links->map(function($link){return $link->contacts;})->flatten()->SortByDesc('pivot.updated_at')->first();
                $last_click_time = "";
                if($last_click_contact)
                {
                    $last_click_time = date('M j, Y H:i:s', (strtotime($last_click_contact->pivot->updated_at) + ($user->profile->timezone->offset * 60 * 60)));
                }

                // open list;
                $country_open_list = \DB::table('campaign_contact')->where('campaign_id', $campaign->id)->where('opened', true)->select('country_name', \DB::raw('COUNT(open_count) as total'))->groupBy('country_name')->orderByDesc('total')->get();
                $domain_open_list = \DB::table('campaign_contact')->where('campaign_id', $campaign->id)->where('opened', true)->select('domain', \DB::raw('COUNT(open_count) as total'))->groupBy('domain')->orderByDesc('total')->get();
                $device_open_list = \DB::table('campaign_contact')->where('campaign_id', $campaign->id)->where('opened', true)->select('device', \DB::raw('COUNT(open_count) as total'))->groupBy('device')->orderByDesc('total')->get();
                $browser_open_list = \DB::table('campaign_contact')->where('campaign_id', $campaign->id)->where('opened', true)->select('browser', \DB::raw('COUNT(open_count) as total'))->groupBy('browser')->orderByDesc('total')->get();

                $links = $campaign->links;
                $link_id_array = [];
                foreach($links as $link)
                {
                    array_push($link_id_array, $link->id);
                }

                $country_click_list = $campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->groupBy('pivot.country_name')->map(function ($item, $key){ return collect($item)->count(); });
                $domain_click_list = $campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->groupBy('pivot.domain')->map(function ($item, $key){ return collect($item)->count(); });
                $device_click_list = $campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->groupBy('pivot.device')->map(function ($item, $key){ return collect($item)->count(); });
                $browser_click_list = $campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->groupBy('pivot.browser')->map(function ($item, $key){ return collect($item)->count(); });

                $total_clicks = $campaign->links->map(function($link){return $link->contacts;})->flatten()->sum('pivot.click_count');

                return view('analysis.campaign')->with(['user' => $user, 'main_user' => $mainUser, 'campaign' => $campaign, 'average_time_to_open' => $average_time_to_open, 'average_time_to_click' => $average_time_to_click, 'last_open_time' => $last_open_time, 'last_click_time' => $last_click_time, 'country_open_list' => $country_open_list, 'domain_open_list' => $domain_open_list, 'device_open_list' => $device_open_list, 'browser_open_list' => $browser_open_list, 'country_click_list' => $country_click_list, 'domain_click_list' => $domain_click_list, 'device_click_list' => $device_click_list, 'browser_click_list' => $browser_click_list, 'total_clicks' => $total_clicks]);

            }
            return back()->with('error', "Unathorized Access");
        }
        return back()->with('error', 'Campaign not found');
    }

    public function downloadOpenersList($campaign_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $campaign = Campaign::find($campaign_id);
        if($campaign)
        {
            if($campaign->sub_account_id == $user->currentAccount->id)
            {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                $contacts = $campaign->contacts()->wherePivot('opened', true)->get();
                $columns = array('Name', 'Email Address', 'Open Count', 'Last Opened');

                $callback = function() use ($contacts, $columns, $user)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach($contacts as $contact) {
                        fputcsv($file, array($contact->name, $contact->email, $contact->pivot->open_count, date('M j, Y H:i:s', (strtotime($contact->pivot->updated_at) + ($user->profile->timezone->offset * 60 * 60)))));
                    }
                    fclose($file);
                };
                return response()->streamDownload($callback, 'openers_list-' . date('d-m-Y-H:i:s').'.csv', $headers);
            }
            return back()->with('error', "Unathorized Access");
        }

        return back()->with('error', 'Campaign not found');
    }

    public function downloadClickersList($campaign_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $campaign = Campaign::find($campaign_id);
        if($campaign)
        {
            if($campaign->sub_account_id == $user->currentAccount->id)
            {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                $contacts = $campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id');
                $columns = array('Name', 'Email Address');

                $callback = function() use ($contacts, $columns, $user)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach($contacts as $contact) {
                        fputcsv($file, array($contact->name, $contact->email));
                    }
                    fclose($file);
                };
                return response()->streamDownload($callback, 'clickers_list-' . date('d-m-Y-H:i:s').'.csv', $headers);
            }
            return back()->with('error', "Unathorized Access");
        }

        return back()->with('error', 'Campaign not found');
    }

    public function downloadLinkList($link_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $link = Link::find($link_id);
        if($link)
        {
            if($link->campaign->sub_account_id == $user->currentAccount->id)
            {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                $contacts = $link->contacts()->get();
                $columns = array('Name', 'Email Address', 'Click Count', 'Last Clicked');

                $callback = function() use ($contacts, $columns, $user)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach($contacts as $contact) {
                        fputcsv($file, array($contact->name, $contact->email, $contact->pivot->click_count, date('M j, Y H:i:s', (strtotime($contact->pivot->updated_at) + ($user->profile->timezone->offset * 60 * 60)))));
                    }
                    fclose($file);
                };
                return response()->streamDownload($callback, 'link_list-' . date('d-m-Y-H:i:s').'.csv', $headers);
            }
            return back()->with('error', "Unathorized Access");
        }

        return back()->with('error', 'Link not found');
    }

    public function downloadComplainedList($campaign_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $campaign = Campaign::find($campaign_id);
        if($campaign)
        {
            if($campaign->sub_account_id == $user->currentAccount->id)
            {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                $contacts = $campaign->contacts()->wherePivot('complained', true)->get();
                $columns = array('Name', 'Email Address', 'Date');

                $callback = function() use ($contacts, $columns, $user)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach($contacts as $contact) {
                        fputcsv($file, array($contact->name, $contact->email, date('M j, Y H:i:s', (strtotime($contact->pivot->updated_at) + ($user->profile->timezone->offset * 60 * 60)))));
                    }
                    fclose($file);
                };
                return response()->streamDownload($callback, 'complainers_list-' . date('d-m-Y-H:i:s').'.csv', $headers);
            }
            return back()->with('error', "Unathorized Access");
        }

        return back()->with('error', 'Campaign not found');
    }

    public function downloadBouncedList($campaign_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $campaign = Campaign::find($campaign_id);
        if($campaign)
        {
            if($campaign->sub_account_id == $user->currentAccount->id)
            {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                $contacts = $campaign->contacts()->wherePivot('bounced', true)->get();
                $columns = array('Name', 'Email Address', 'Date');

                $callback = function() use ($contacts, $columns, $user)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach($contacts as $contact) {
                        fputcsv($file, array($contact->name, $contact->email, date('M j, Y H:i:s', (strtotime($contact->pivot->updated_at) + ($user->profile->timezone->offset * 60 * 60)))));
                    }
                    fclose($file);
                };
                return response()->streamDownload($callback, 'soft_bounced_list-' . date('d-m-Y-H:i:s').'.csv', $headers);
            }
            return back()->with('error', "Unathorized Access");
        }

        return back()->with('error', 'Campaign not found');
    }

    public function downloadHardBouncedList($campaign_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $campaign = Campaign::find($campaign_id);
        if($campaign)
        {
            if($campaign->sub_account_id == $user->currentAccount->id)
            {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                $contacts = $campaign->contacts()->wherePivot('hard_bounced', true)->get();
                $columns = array('Name', 'Email Address', 'Date');

                $callback = function() use ($contacts, $columns, $user)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach($contacts as $contact) {
                        fputcsv($file, array($contact->name, $contact->email, date('M j, Y H:i:s', (strtotime($contact->pivot->updated_at) + ($user->profile->timezone->offset * 60 * 60)))));
                    }
                    fclose($file);
                };
                return response()->streamDownload($callback, 'hard_bounced_list-' . date('d-m-Y-H:i:s').'.csv', $headers);
            }
            return back()->with('error', "Unathorized Access");
        }

        return back()->with('error', 'Campaign not found');
    }
}
