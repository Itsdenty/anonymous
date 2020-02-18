<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Contact;
use Carbon\Carbon;
use App\ContactTag;
use App\Tag;
use function GuzzleHttp\json_decode;
use App\Segment;
use App\ContactSegment;
use App\GeneralUnsubscriber;
use App\Activity;
use DateTime;

class ContactController extends Controller
{

    public function __construct(){

        $this->middleware('auth');
    }

    public function contactsexport() {

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

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        
        $contacts = $user->currentAccount->contacts->where('marked', true);
        $columns = array('Name', 'Email Address', 'Subscription Date');

        if(!$user->profile)
        {
            return redirect('settings')->with('status', "Please Update Profile");
        }

        $callback = function() use ($contacts, $columns, $user)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
    
            foreach($contacts as $contact) {
                fputcsv($file, array($contact->name, $contact->email, date('M j, Y H:i:s', (strtotime($contact->created_at) + ($user->profile->timezone->offset * 60 * 60)))));
            }
            fclose($file);
        };
        return response()->streamDownload($callback, 'Contact_list-' . date('d-m-Y-H:i:s').'.csv', $headers);
    }

    public function store(Request $request) {
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

        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $data = $request->all();

        
        $contact = new Contact();
        $contact->sub_account_id = $user->currentAccount->id;
        $contact->firstname = $request->firstname;
        $contact->lastname = $request->lastname;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->sub_date = Carbon::now();

        $contact->save();
        Activity::create(['content' => 'Added to Contact List', 'category' => 'added', 'contact_id' => $contact->id]);


        $general_unsubscriber = GeneralUnsubscriber::where('email', $contact->email)->where('user_id', $user->id)->first();
        if($general_unsubscriber)
        {
            $contact->unsubscribed = true;
        }
        else
        {
            $contact->unsubscribed = false;
        }
        $contact->save();
         
        if(isset($data["contact_tags"]))
        {
            foreach($request->contact_tags as $ct) {
                ContactTag::create([
                    'sub_account_id' => $user->currentAccount->id,
                    'contact_id' => $contact->id,
                    "tag_id" => $ct['id']
                ]);
            }
        }

        $contact->tags()->detach();
        if(isset($data["tags"]))
        {
            $contact->tags()->attach($data["tags"]);
        }

        foreach($data as $key => $input)
        {
            if (strpos($key, 'custom') === 0) {
                if($input)
                {
                    $key_array = explode('_', $key);
                    $contact->contactAttributes()->attach((int)$key_array[1], ['value' => $input]);
                }
            }
        }

        if($request->ajax())
        {
            return;
        }

        return back()->with('status', "Contact Added Successfully");

    }

    function destroy(Contact $contact) {

        $con_from = ContactTag::where('contact_id', $contact->id)->get();

        $con_from->each->delete();

        $contact->delete();

        if(request()->wantsJson()){

            return response(['status', 'contact deleted successfully']);
        }
        return back(); 
    }

    public function updateCheck(Request $request, Contact $contact)
    {
        $this->validate($request, [
            'marked' => 'required|boolean'
        ]);
        if($contact){
            $contact->update([
                'marked' => $request->marked
            ]);
        }
        
        $all_checked = $contact->subaccount->contacts->where('marked', false)->count() == 0 && $contact->subaccount->contacts->count() > 0 ? true : false;

        $one_marked = $contact->subaccount->contacts->where('marked', true)->count() > 0 && $contact->subaccount->contacts->count() > 0 ? true : false;

        return response()->json(['contact' => $contact, 'all_checked' => $all_checked, 'one_marked' => $one_marked]);

        // return response($contact, 201);
    }

    public function updateAll(Request $request)
    {
        $data = $request->validate([
            'marked' => 'required|boolean',
        ]);
        //to do bulk update we use "query"
        // Contact::query()->update($data);

        // return response('updated', 200);

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

        Contact::where('sub_account_id', $user->currentAccount->id)->update(['marked' => $request->marked]);
        $all_checked = $user->currentAccount->contacts->where('marked', false)->count() == 0 && $user->currentAccount->contacts->count() > 0 ? true : false;

        $one_marked = $user->currentAccount->contacts->where('marked', true)->count() > 0 && $user->currentAccount->contacts->count() > 0 ? true : false;

        return response()->json(['all_checked' => $all_checked, 'one_marked' => $one_marked]);
    }

    public function destroyMarked(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        };

        if($mainUser->member)
        {
            if($mainUser->member->role == 2)
            {
                return back()->with('error', 'This page can only be accessed by owners');
            }
        }

        $marked_contacts = $user->currentAccount->contacts->where('marked', true);

        foreach($marked_contacts as $contact)
        {
            $contact->delete();
        }

        if(request()->wantsJson()){

            return response(['status', 'contact deleted successfully']);
        }
        return back()->with('status', 'Contact(s) Deleted Successfully'); 
    }

    public function purgeList($number, Request $request)
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

        $lastCampaigns = $user->currentAccount->campaigns->sortByDesc('id')->take(intval($number));
        
        $receivers = $lastCampaigns->map(function($campaign){
            return $campaign->contacts()->wherePivot('sent', true)->get();
        })->flatten()->unique('id');
        $openers = $lastCampaigns->map(function($campaign){
            return $campaign->contacts()->wherePivot('opened', true)->get();
        })->flatten()->unique('id');

        $receivers_array = [];
        foreach($receivers as $receiver)
        {
            array_push($receivers_array, $receiver->id);
        }

        $openers_array = [];
        foreach($openers as $opener)
        {
            array_push($openers_array, $opener->id);
        }


        $diff = array_diff($receivers_array, $openers_array);

        if($request->ajax())
        {
            return response()->json(['result' => $diff], 200);
        }

        return $diff;
    }

    public function contactDashboard()
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

        // $contacts = $user->currentAccount->contacts;
        $contacts = Contact::where('sub_account_id', $user->currentAccount->id)->paginate(10);
        $tags = $user->currentAccount->tags;
        $segments = $user->currentAccount->segments;

        $average_open_rate = 0;

        $num_of_contacts = Contact::where('sub_account_id', $user->currentAccount->id)->count();

        $num_of_opens = $user->currentAccount->campaigns->map(function($campaign){
            return $campaign->contacts()->wherePivot('opened', true)->get();
        })->flatten()->unique('id')->count();

        $num_of_clicks = $user->currentAccount->campaigns->map(function($campaign){
            return $campaign->links->map(function($link){
                return $link->contacts;
            });
        })->flatten()->unique('id')->count();

        $num_of_sent = $user->currentAccount->campaigns->map(function($campaign){
            return $campaign->contacts->where('sent', true);
        })->flatten()->count();

        $contact_attributes = $user->currentAccount->contactAttributes;
        
        return view('contacts.contacts')->with(['user' => $user, 'main_user' => $mainUser, 'contacts' => $contacts, 'tags' => $tags, 'segments' => $segments, 'num_of_opens' => $num_of_opens, 'num_of_clicks' => $num_of_clicks, 'num_of_sent' => $num_of_sent, 'contact_attributes' => $contact_attributes, 'num_of_contacts' => $num_of_contacts]);
    }

    public function deleteContact($contact_id)
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
        
        $contact = Contact::find($contact_id);
        if($contact)
        {
            if($contact->sub_account_id == $user->currentAccount->id)
            {
                $contact->delete();
                return back()->with('status', "Contact Deleted Successfully");
            }
            return back()->with('error', "Unathorized Access");
        }
        return back()->with('error', 'Contact not found');
    }

    public function editContact($contact_id)
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

        $contact = Contact::find($contact_id);

        if($contact)
        {
            if($contact->sub_account_id == $user->currentAccount->id)
            {
                $contact_tags = $contact->tags;
                $current_account_tags = $user->currentAccount->tags;
                $contact_attributes = $user->currentAccount->contactAttributes;
                $contact_activities = $contact->activities;

                $total_links_count = $contact->campaigns->map(function($campaign){
                    return $campaign->links;
                })->flatten()->count();

                return view('contacts.editcontact')->with(['user' => $user, 'main_user' => $mainUser, 'contact' => $contact, 'contact_tags' => $contact_tags, 'current_account_tags' => $current_account_tags, 'contact_attributes' => $contact_attributes, 'contact_activities' => $contact->activities, 'total_links_count' => $total_links_count]);
            }
            return back()->with('error', "Unauthorized Access");
        }
        return redirect('contacts');
    }

    public function updateContact(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);
        
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

        $data = $request->all();
        $contact = Contact::find($data['contact_id']);

        if($contact)
        {
            if($contact->sub_account_id == $user->currentAccount->id)
            {
                $contact->firstname = $data["firstname"];
                $contact->lastname = $data["lastname"];
                $contact->email = $data["email"];
                $contact->phone = $data["phone"];
                $contact->save();

                $general_unsubscriber = GeneralUnsubscriber::where('email', $contact->email)->where('user_id', $user->id)->first();
                if($general_unsubscriber)
                {
                    $contact->unsubscribed = true;
                }
                else
                {
                    $contact->unsubscribed = false;
                }
                $contact->save();

                $contact->tags()->detach();
                if(isset($data["tags"]))
                {
                    $contact->tags()->attach($data["tags"]);
                }

                $contact->contactAttributes()->detach();
                foreach($data as $key => $input)
                {
                    if (strpos($key, 'custom') === 0) {
                        if($input)
                        {
                            $key_array = explode('_', $key);
                            $contact->contactAttributes()->attach((int)$key_array[1], ['value' => $input]);
                        }
                    }
                }
                
                return back()->with('status', "Contact Updated Successfully");
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with('error', "Contact not found");
    }

    public function updateActivities(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();

        \Log::info($data);

        if($data['show_option'] == "all")
        {
            $activities = Activity::where('contact_id', $data['contact_id'])->whereDate('created_at', '>=', $data['start_date'])->whereDate('created_at', '<=', $data['end_date'])->orderBy('id', 'DESC')->get();
        }
        else
        {
            $activities = Activity::where('contact_id', $data['contact_id'])->where('category', $data['show_option'])->whereDate('created_at', '>=', $data['start_date'])->whereDate('created_at', '<=', $data['end_date'])->orderBy('id', 'DESC')->get();
        }

        $output = [];
        foreach($activities as $activity)
        {
            array_push($output, ['content' => $activity->content, 'created_at' => (new DateTime(date('Y-m-d H:i', (strtotime($activity->created_at) + ($user->profile->timezone->offset * 60 * 60)))))->format('M j, Y H:i:s')]);
        }

        return response()->json(['activities' => $output]);
    }

    public function unsubscribeContact($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $contact = Contact::find($id);
        if($contact)
        {
                
            $general_unsubscriber = GeneralUnsubscriber::where('email', $contact->email)->where('user_id', $user->id)->first();
            if(!$general_unsubscriber)
            {
                $general_unsubscriber = new GeneralUnsubscriber();
                $general_unsubscriber->email = $contact->email;
                $general_unsubscriber->user_id = $user->id;
                $general_unsubscriber->save();
            }
            Activity::create(['content' => 'User unsubscribed Contact', 'category' => 'unsubscribes', 'contact_id' => $contact->id]);
            
            $contact->unsubscribed = true;
            $contact->save();
            return back()->with('status', "Contact Unsubscribed Successfully");
        }
        return back()->with('error', "Contact not found");
    }
}
