<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SmsCampaign;
use Auth;
use Carbon\Carbon;
use Image;
use Illuminate\Support\Str;
use File;

class SmsCampaignController extends Controller
{
    //
    public function viewSmsCampaigns()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $num_of_contacts = $user->currentAccount->contacts->where('phone', '!=', null)->count();

        $num_of_sents = $user->currentAccount->smsCampaigns->map(function($campaign){
            return $campaign->contacts()->wherePivot('sent', true)->get();
        })->flatten()->unique('id')->count();

        $num_of_clicks = $user->currentAccount->smsCampaigns->map(function($campaign){
            return $campaign->links->map(function($link){
                return $link->contacts;
            });
        })->flatten()->unique('id')->count();

        $sms_campaigns = SmsCampaign::where('sub_account_id', $user->currentAccount->id)->orderBy('id', 'desc')->paginate(10);

        return view('sms_mms.campaigns.campaigns')->with(['user' => $user, 'main_user' => $mainUser, 'sms_campaigns' => $sms_campaigns, 'num_of_contacts' => $num_of_contacts, 'num_of_sents' => $num_of_sents, 'num_of_clicks' => $num_of_clicks]);
    }


    public function createSmsCampaign()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $sms_mms_integrations = $user->currentAccount->smsMmsIntegrations;

        if($sms_mms_integrations->isEmpty())
        {
            return redirect('sms_mms_settings')->with('status', "Please SMS/MMS Config to create SMS/MMS Campaign(s)");
        }

        $sms_campaign = new SmsCampaign();
        $sms_campaign->sub_account_id = $user->currentAccount->id;
        $sms_campaign->title = "New SMS/MMS Campaign";
        $sms_campaign->save();

        return redirect('create/'.$sms_campaign->id.'/smscampaign')->with(['sms_campaign' => $sms_campaign]);
    }

    public function viewSmsCampaign($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $sms_campaign = SmsCampaign::find($id);
        if($sms_campaign)
        {
            if($sms_campaign->sub_account_id == $user->currentAccount->id)
            {
                $sms_mms_integrations = $user->currentAccount->smsMmsIntegrations;

                $contacts = $user->currentAccount->contacts->where('unsubscribed', false)->where('phone', '!=', null);

                $sms_campaign->contacts()->detach();
                foreach($contacts as $contact) {
                    $sms_campaign->contacts()->attach($contact->id);
                }

                return view('sms_mms.campaigns.create')->with(['user' => $user, 'main_user' => $mainUser, 'sms_campaign' => $sms_campaign, 'contacts' => $contacts, 'sms_mms_integrations' => $sms_mms_integrations]);
            }
            return redirect('sms_campaigns')->with('error', 'Unauthorized access to SMS/MMS Campaign');
        }
        return redirect('sms_campaigns')->with('error', 'SMS/MMS Campaign not found');
    }

    public function editSmsCampaign($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $sms_campaign = SmsCampaign::find($id);
        if($sms_campaign)
        {
            if($sms_campaign->sub_account_id == $user->currentAccount->id)
            {
                $sms_mms_integrations = $user->currentAccount->smsMmsIntegrations;

                $contacts = $user->currentAccount->contacts->where('unsubscribed', false)->where('phone', '!=', null);

                return view('sms_mms.campaigns.edit')->with(['user' => $user, 'main_user' => $mainUser, 'sms_campaign' => $sms_campaign, 'contacts' => $contacts, 'sms_mms_integrations' => $sms_mms_integrations]);
            }
            return redirect('sms_campaigns')->with('error', 'Unauthorized access to SMS/MMS Campaign');
        }
        return redirect('sms_campaigns')->with('error', 'SMS/MMS Campaign not found');
    }

    public function store(SmsCampaign $sms_campaign, Request $request)
    {
        $sms_campaign->contacts()->detach();
        if($request->smscampaign_contacts){
            foreach($request->smscampaign_contacts as $ct) {
                $sms_campaign->contacts()->attach($ct['id']);
            }

        }

        if($request->filter_query_string)
        {
            $sms_campaign->filter_query = $request->filter_query_string;
            $sms_campaign->save();
        }
    }

    public function saveSmsCampaign(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();

        $sms_campaign = SmsCampaign::find($data['sms_campaign_id']);
        if(!$sms_campaign)
        {
            $sms_campaign = new SmsCampaign();
        }

        if($sms_campaign->status  != 'draft' && $sms_campaign->status != 'later')
        {
            return redirect('sms_campaigns')->with('error', 'Cannot Edit Campaign already Sent or in Progress');
        }

        $sms_campaign->title = $data['title'];
        $sms_campaign->sms_mms_integration_id = $data['integration'];
        $sms_campaign->content = $data['content'];
        $sms_campaign->status = 'draft';

        if($request->hasFile('mms_pic'))
        {
            $filePath = public_path() . '/mms_images/';
            if (!File::isDirectory($filePath))
            { 
                File::makeDirectory($filePath);
            }

            $name = Str::random(32) . time();
            $img = Image::make($data['mms_pic'])->encode('png', 100);
            $img->save($filePath.$name.'.png');

            $sms_campaign->image_url = 'mms_images/'.$name.'.png';
        }

        $sms_campaign->save();

        return $sms_campaign;
    }

    public function updateSmsCampaign(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();

        $sms_campaign = SmsCampaign::find($data['sms_campaign_id']);
        if(!$sms_campaign)
        {
            $sms_campaign = new SmsCampaign();
        }

        if($sms_campaign->status  != 'draft' && $sms_campaign->status != 'later')
        {
            return redirect('sms_campaigns')->with('error', 'Cannot Edit Campaign already Sent or in Progress');
        }

        $sms_campaign->title = $data['title'];
        $sms_campaign->sms_mms_integration_id = $data['integration'];
        $sms_campaign->content = $data['content'];

        if($request->hasFile('mms_pic'))
        {
            $filePath = public_path() . '/mms_images/';
            if (!File::isDirectory($filePath))
            { 
                File::makeDirectory($filePath);
            }

            $name = Str::random(32) . time();
            $img = Image::make($data['mms_pic'])->encode('png', 100);
            $img->save($filePath.$name.'.png');

            $sms_campaign->image_url = 'mms_images/'.$name.'.png';
        }

        $message = "";

        if($data['sending'] == 'later')
        {
            $sms_campaign->status = 'later';
            $sms_campaign->send_date = $data['send_date'];
            $sms_campaign->actual_send_date = date('Y-m-d H:i', (strtotime($data['send_date']) - ($user->profile->timezone->offset * 60 * 60)));

            $message = "Campaign will be sent at ". $data['send_date'];
        }
        else
        {
            $sms_campaign->status = 'sent';
            $sms_campaign->actual_send_date = \Carbon\Carbon::now();
            $message = "Campaign Sent" ;
        }

        $sms_campaign->save();

        return redirect('sms_campaigns')->with('status', $message);

    }

    public function deleteSmsCampaign($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }
        
        $sms_campaign = SmsCampaign::find($id);
        if($sms_campaign)
        {
            if($sms_campaign->sub_account_id == $user->currentAccount->id)
            {
                if($sms_campaign->status != 'progress')
                {
                    if($sms_campaign->image_url)
                    {
                        $file = public_path().'/'.$sms_campaign->image_url;
                        if(File::isFile($file)){
                            File::delete($file);
                        }
                    }
                    $sms_campaign->delete();
                    return back()->with('status', "Campaign Deleted Successfully");
                }
                return back()->with('error', "Can't delete a Campaign in Progress");
            }
            return back()->with('error', "Unathorized Access");
        }
        return back()->with('error', 'Campaign not found');
    }

    public function deleteMmsImage($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }
        
        $sms_campaign = SmsCampaign::find($id);
        if($sms_campaign)
        {
            if($sms_campaign->sub_account_id == $user->currentAccount->id)
            {
                if($sms_campaign->status != 'progress')
                {
                    if($sms_campaign->image_url)
                    {
                        $file = public_path().'/'.$sms_campaign->image_url;
                        if(File::isFile($file)){
                            File::delete($file);
                        }
                        $sms_campaign->image_url = null;
                        $sms_campaign->save();
                    }
                }
            }
        }
    }

    public function duplicateSmsCampaign($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $sms_campaign = SmsCampaign::find($id);
        if($sms_campaign)
        {
            if($sms_campaign->sub_account_id == $user->currentAccount->id)
            {
                $dup_campaign = new SmsCampaign();
                $dup_campaign->title = $sms_campaign->title.' (duplicate)';
                $dup_campaign->sms_mms_integration_id = $sms_campaign->sms_mms_integration_id;
                $dup_campaign->content = $sms_campaign->content;
                $dup_campaign->status = 'draft';
                $dup_campaign->sub_account_id = $sms_campaign->sub_account_id;
                if($sms_campaign->image_url)
                {
                    $file = public_path().'/'.$sms_campaign->image_url;
                    if(File::isFile($file)){
                        $name = Str::random(32) . time();
                        $success = File::copy($file, public_path() . '/mms_images/' . $name . '.png');
                        $dup_campaign->image_url = 'mms_images/'.$name.'.png';
                    }
                }
                $dup_campaign->save();

                return redirect('sms_campaigns')->with('status', "Campaign Duplicated Successfully");
            }
            return redirect('sms_campaigns')->with('error', "Unauthorized Access");
        }
        return redirect('sms_campaigns')->with('error', 'Campaign not found');
    }

    public function viewAnalysisSms($id)
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

        $campaign = SmsCampaign::find($id);
        if($campaign)
        {
            if($campaign->sub_account_id == $user->currentAccount->id)
            {
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
                
                return view('analysis.sms_campaign')->with(['user' => $user, 'main_user' => $mainUser, 'sms_campaign' => $campaign, 'average_time_to_click' => $average_time_to_click, 'last_click_time' => $last_click_time, 'country_click_list' => $country_click_list, 'domain_click_list' => $domain_click_list, 'device_click_list' => $device_click_list, 'browser_click_list' => $browser_click_list, 'total_clicks' => $total_clicks]);

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

        $campaign = SmsCampaign::find($campaign_id);
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

        $link = SmsCampaignLink::find($link_id);
        if($link)
        {
            if($link->smsCampaign->sub_account_id == $user->currentAccount->id)
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

    public function trackLink($replacement_url, $contact_id)
    {
        $link = SmsCampaignLink::where('replacement_url', $replacement_url)->first();
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

            $contact->country_name = $country_name;
            $contact->save();
            
            return Redirect::to($link->actual_url);
        }

        return "Link not found";
    }
}
