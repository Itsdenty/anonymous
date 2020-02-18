<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Segment;
use App\Contact;
use Auth;
use App\ContactSegment;

class SegmentController extends Controller
{
    public function contact_segment(){

        $user = Auth::user();
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }
        return response()
            ->json([
                'collection' => Contact::where('sub_account_id', $user->currentAccount->id)->where('unsubscribed', false)->advancedFilter()
        ]);
    }

    public function sms_contact_segment(){

        $user = Auth::user();
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }
        return response()
            ->json([
                'collection' => Contact::where('sub_account_id', $user->currentAccount->id)->where('phone', '!=', null)->where('unsubscribed', false)->advancedFilter()
        ]);
    }
    
    public function store(Request $request){
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $input = $this->validate(request(), [
            'name' => 'required',
        ]);

        $segment = new Segment();
        $segment->sub_account_id = $user->currentAccount->id;
        $segment->name = $request->name;
        $segment->filter_query = $request->filter_query_string;
        $segment->save();

        foreach($request->segment_contacts as $ct) {
            ContactSegment::create([
                'sub_account_id' => $user->currentAccount->id,
                "contact_id" => $ct['id'],
                'segment_id' => $segment->id,
            ]);
        }
    }

    public function editSegment($segment_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $segment = Segment::find($segment_id);

        if($segment)
        {
            if($segment->sub_account_id == $user->currentAccount->id)
            {   
                return view('contacts.editsegment')->with(['user' => $user, 'main_user' => $mainUser, 'segment' => $segment]);
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with('error', "Segment not found");
    }

    public function updateSegment(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();

        $segment = Segment::find($data['segment_id']);
        if($segment)
        {
            if($segment->sub_account_id == $user->currentAccount->id)
            {   
                $segment->name = $data["name"];
                $segment->save();
                return back()->with('status', "Segment Updated Successfully");
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with('error', "Segment not found");
    }

    public function deleteSegment($segment_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $segment = Segment::find($segment_id);

        if($segment)
        {
            if($segment->sub_account_id == $user->currentAccount->id)
            {   
                $segment->contacts()->detach();
                $segment->delete();
                return redirect('contacts')->with('status', 'Segment Deleted Successfully');
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with('error', "Tag not found");
    }
}
