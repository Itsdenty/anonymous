<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Sequence;
use App\SequenceEmail;
use App\SequenceEmailLink;
use App\Contact;
use App\Activity;
use App\Http\Controllers\DetectController;

class SequenceEmailController extends Controller
{
    //
    public function viewContents($sequence_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $sequence = Sequence::find($sequence_id);
        if($sequence)
        {
            if($sequence->sub_account_id == $user->currentAccount->id)
            {
                return view('sequences.contents')->with(['user' => $user, 'main_user' => $mainUser, 'sequence' => $sequence]);
            }
            return back()->with('error', "Unauthorized Access");
        }

        return back()->with('error', "Sequence not found");
    }

    public function saveContent(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $data = $request->all();

        $sequence = Sequence::find($data['sequence_id']);
        if($sequence)
        {
            if($sequence->sub_account_id == $user->currentAccount->id)
            {
                $sequence_email = "";
                
                if(isset($data['content_id']))
                {
                    $message = "Email Updated Successfully";
                    $sequence_email = SequenceEmail::find($data['content_id']);
                }

                if(!$sequence_email)
                {
                    $sequence_email = new SequenceEmail();
                    $message = "Email Added Successfully";
                }

                $sequence_email->subject = $data['subject'];
                $sequence_email->send_time = $data['send_time'];
                $sequence_email->content = $data['email_content'];
                if(isset($data['time_value']))
                {
                    $sequence_email->time_value = $data['time_value'];
                }
                $sequence_email->time_unit = $data['time_unit'];
                $sequence_email->sequence_id = $sequence->id;
                $sequence_email->save();

                if($sequence_email->time_value == 0 || $sequence_email->time_value == null)
                {
                    $sequence_email->time_in_seconds = 0;
                }
                else
                {
                    if($sequence_email->time_unit == 'minute')
                    {
                        $sequence_email->time_in_seconds = $sequence_email->time_value * 60;
                    }
                    else if($sequence_email->time_unit == 'hour')
                    {
                        $sequence_email->time_in_seconds = $sequence_email->time_value * 60 * 60;
                    }
                    else
                    {
                        $sequence_email->time_in_seconds = $sequence_email->time_value * 60 * 60 * 24;
                    }
                }
                $sequence_email->save();

                if(!$sequence_email->sort_id)
                {
                    $sequence_email->sort_id = $sequence->sequenceEmails->count() + 1;
                    $sequence_email->save();
                }
                
                return back()->with('status', $message);
            }
            return back()->with('error', "Unauthorized Access");
        }

        return back()->with('error', "Sequence not found");
    }

    public function editContent($sequence_id, $content_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $sequence = Sequence::find($sequence_id);
        $sequence_email = SequenceEmail::find($content_id);
        if($sequence)
        {
            if($sequence_email)
            {
                if($sequence->sub_account_id == $user->currentAccount->id && $sequence_email->sequence_id == $sequence->id)
                {
                    return response()->json(['email' => $sequence_email->toJson()]);
                }
                return response()->json(['error' => "Unauthorized access"], 404);
            }
            return response()->json(['error' => "Email not found"], 404);
        }

        return response()->json(['error' => "Sequence not found"], 404);
    }

    public function deleteContent($content_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $sequence_email = SequenceEmail::find($content_id);
        if($sequence_email)
        {
            if($sequence_email->sequence->sub_account_id == $user->currentAccount->id)
            {
                $sequence_email->delete();
                return back()->with('status', "Email Deleted Successfully");
            }
            return back()->with('error', "Unauthorized access");
        }
        return back()->with('error', "Email not found");
    }

    public function activateSequenceEmail($content_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $sequence_email = SequenceEmail::find($content_id);
        if($sequence_email)
        {
            if($sequence_email->sequence->sub_account_id == $user->currentAccount->id)
            {
                $sequence_email->status = "active";
                $sequence_email->save();

                return response()->json(['success' => "Sequence Email Activated"], 200);
            }
            return response()->json(['error' => "Unauthorized access"], 404);
        }
        return response()->json(['error' => "Email not found"], 404);
    }

    public function deactivateSequenceEmail($content_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $sequence_email = SequenceEmail::find($content_id);
        if($sequence_email)
        {
            if($sequence_email->sequence->sub_account_id == $user->currentAccount->id)
            {
                $sequence_email->status = "inactive";
                $sequence_email->save();

                return response()->json(['success' => "Sequence Email Deactivated"], 200);
            }
            return response()->json(['error' => "Unauthorized access"], 404);
        }
        return response()->json(['error' => "Email not found"], 404);
    }

    public function duplicateContent($content_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $sequence_email = SequenceEmail::find($content_id);
        if($sequence_email)
        {
            if($sequence_email->sequence->sub_account_id == $user->currentAccount->id)
            {
                $dup_email = new SequenceEmail();
                $dup_email->subject = $sequence_email->subject;
                $dup_email->content = $sequence_email->content;
                $dup_email->send_time = $sequence_email->send_time;
                $dup_email->time_value = $sequence_email->time_value;
                $dup_email->time_unit = $sequence_email->time_unit;
                $dup_email->sequence_id = $sequence_email->sequence_id;
                $dup_email->save();

                return back()->with('status', "Email Duplicated Successfully");
            }
            return back()->with('error', "Unauthorized access");
        }
        return back()->with('error', "Email not found");
    }

    public function sortEmail($sequence_id, Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $data = $request->all();

        $sequence = Sequence::find($sequence_id);
        if($sequence)
        {
            for($i = 0; $i < sizeof($data['order']); $i++)
            {
                $sequence_email = SequenceEmail::find($data['order'][$i]);
                if($sequence_email)
                {
                    $sequence_email->sort_id = $i + 1;
                    $sequence_email->save();                    
                }

            }
            return response()->json(['status' => "Sort Successful"]);
        }
        return response()->json(['error' => "Sequence not found"], 404);
    }

    public function trackEmail($email_id, $contact_id)
    {
        $email = SequenceEmail::find($email_id);
        $contact = Contact::find($contact_id);

        $vdata = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));
        $vbrowser = explode(' ',DetectController::browser());

        $device = DetectController::isMobile() ? 'Mobile' : 'Desktop';
        $browser = $vbrowser[0];
        $country_name = $vdata['geoplugin_countryName'];
        
        if($email && $contact)
        {
            $contact_details = $email->contacts->find($contact->id);
            $domain_name = explode('@', $contact->email)[1];
            $count = $contact_details->pivot->open_count;
            $email->contacts()->updateExistingPivot($contact_id, ['opened' => true, 'open_count' => $count + 1, 'device' => $device, 'browser' => $browser, 'domain' => $domain_name, 'country_name' => $country_name]);

            $contact->country_name = $country_name;
            $contact->save();
        }
    }

    public function trackLink($replacement_url, $contact_id)
    {
        $link = SequenceEmailLink::where('replacement_url', $replacement_url)->first();
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

    public function viewAnalysis($email_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $email = SequenceEmail::find($email_id);
        if($email)
        {
            if($email->sequence->sub_account_id == $user->currentAccount->id)
            {
                $total_open_time_difference = 0;
                $average_time_to_open = 0;
                $open_contacts = $email->contacts()->wherePivot('opened', true)->get();
                $open_contacts_count = $email->contacts()->wherePivot('opened', true)->count();
                foreach($open_contacts as $con)
                {
                    $total_open_time_difference += strtotime($con->pivot->updated_at) - strtotime($con->pivot->send_time);
                }
                if($open_contacts_count != 0)
                {
                    $average_time_to_open = intdiv($total_open_time_difference, $open_contacts_count);
                }

                $last_opened_contact = $email->contacts()->wherePivot('opened', true)->orderBy('pivot_updated_at', 'desc')->first();
                $last_open_time = "";
                if($last_opened_contact)
                {
                    $last_open_time = date('M j, Y H:i:s', (strtotime($last_opened_contact->pivot->updated_at) + ($user->profile->timezone->offset * 60 * 60)));
                }

                $total_click_time_difference = 0;
                $average_time_to_click = 0;
                $click_contacts = $email->links->map(function($link){return $link->contacts;})->flatten()->unique('id');
                $click_contacts_count = $email->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->count();
                foreach($click_contacts as $con)
                {
                    $total_click_time_difference += strtotime($con->pivot->created_at) - strtotime($con->pivot->send_time);
                }
                if($click_contacts_count != 0)
                {
                    $average_time_to_click = intdiv($total_click_time_difference, $click_contacts_count);
                }

                $last_click_contact = $email->links->map(function($link){return $link->contacts;})->flatten()->SortByDesc('pivot.updated_at')->first();
                $last_click_time = "";
                if($last_click_contact)
                {
                    $last_click_time = date('M j, Y H:i:s', (strtotime($last_click_contact->pivot->updated_at) + ($user->profile->timezone->offset * 60 * 60)));
                }

                // open list;
                $country_open_list = \DB::table('contact_sequence_email')->where('sequence_email_id', $email->id)->where('opened', true)->select('country_name', \DB::raw('COUNT(open_count) as total'))->groupBy('country_name')->orderByDesc('total')->get();
                $domain_open_list = \DB::table('contact_sequence_email')->where('sequence_email_id', $email->id)->where('opened', true)->select('domain', \DB::raw('COUNT(open_count) as total'))->groupBy('domain')->orderByDesc('total')->get();
                $device_open_list = \DB::table('contact_sequence_email')->where('sequence_email_id', $email->id)->where('opened', true)->select('device', \DB::raw('COUNT(open_count) as total'))->groupBy('device')->orderByDesc('total')->get();
                $browser_open_list = \DB::table('contact_sequence_email')->where('sequence_email_id', $email->id)->where('opened', true)->select('browser', \DB::raw('COUNT(open_count) as total'))->groupBy('browser')->orderByDesc('total')->get();

                // $links = $email->links;
                // $link_id_array = [];
                // foreach($links as $link)
                // {
                //     array_push($link_id_array, $link->id);
                // }

                $country_click_list = $email->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->groupBy('pivot.country_name')->map(function ($item, $key){ return collect($item)->count(); });
                $domain_click_list = $email->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->groupBy('pivot.domain')->map(function ($item, $key){ return collect($item)->count(); });
                $device_click_list = $email->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->groupBy('pivot.device')->map(function ($item, $key){ return collect($item)->count(); });
                $browser_click_list = $email->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->groupBy('pivot.browser')->map(function ($item, $key){ return collect($item)->count(); });

                $total_clicks = $email->links->map(function($link){return $link->contacts;})->flatten()->sum('pivot.click_count');
                
                return view('analysis.sequence_email')->with(['user' => $user, 'main_user' => $mainUser, 'email' => $email, 'average_time_to_open' => $average_time_to_open, 'average_time_to_click' => $average_time_to_click, 'last_open_time' => $last_open_time, 'last_click_time' => $last_click_time, 'country_open_list' => $country_open_list, 'domain_open_list' => $domain_open_list, 'device_open_list' => $device_open_list, 'browser_open_list' => $browser_open_list, 'country_click_list' => $country_click_list, 'domain_click_list' => $domain_click_list, 'device_click_list' => $device_click_list, 'browser_click_list' => $browser_click_list, 'total_clicks' => $total_clicks]);

            }
            return back()->with('error', "Unathorized Access");
        }
        return back()->with('error', 'Email not found');
    }

    public function downloadOpenersList($email_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $email = SequenceEmail::find($email_id);
        if($email)
        {
            if($email->sequence->sub_account_id == $user->currentAccount->id)
            {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );
                
                $contacts = $email->contacts()->wherePivot('opened', true)->get();
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

        return back()->with('error', 'Email not found');
    }

    public function downloadClickersList($email_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $email = SequenceEmail::find($email_id);
        if($email)
        {
            if($email->sequence->sub_account_id == $user->currentAccount->id)
            {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );
                
                $contacts = $email->links->map(function($link){return $link->contacts;})->flatten()->unique('id');
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

        return back()->with('error', 'Email not found');
    }

    public function downloadLinkList($link_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $link = SequenceEmailLink::find($link_id);
        if($link)
        {
            if($link->sequenceEmail->sequence->sub_account_id == $user->currentAccount->id)
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

    public function downloadComplainedList($email_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $email = SequenceEmail::find($email_id);
        if($email)
        {
            if($email->sequence->sub_account_id == $user->currentAccount->id)
            {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );
                
                $contacts = $email->contacts()->wherePivot('complained', true)->get();
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

        return back()->with('error', 'Email not found');
    }

    public function downloadBouncedList($email_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $email = SequenceEmail::find($email_id);
        if($email)
        {
            if($email->sequence->sub_account_id == $user->currentAccount->id)
            {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );
                
                $contacts = $email->contacts()->wherePivot('bounced', true)->get();
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

        return back()->with('error', 'Email not found');
    }

    public function downloadHardBouncedList($email_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $email = SequenceEmail::find($email_id);
        if($email)
        {
            if($email->sequence->sub_account_id == $user->currentAccount->id)
            {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );
                
                $contacts = $email->contacts()->wherePivot('hard_bounced', true)->get();
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

        return back()->with('error', 'Email not found');
    }
}
