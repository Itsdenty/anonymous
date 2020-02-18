<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use Auth;
use App\Contact;
use App\ContactTag;

class TagController extends Controller
{
    public function __construct(){

        $this->middleware('auth');

    }

    public function store(Request $request) {

        $user = Auth::user();
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }
        
        $this->validate($request, [
            'name' => 'required|max:191|unique:tags'
        ]);
        $tag= new Tag;
        $tag->sub_account_id = $user->currentAccount->id;
        $tag->name = $request->name;

        $tag->save();

        if($request->ajax())
        {
            return $tag;
        }
        return back()->with('status', "Tag Created Successfully");
    }

    public function editTag($tag_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $tag = Tag::find($tag_id);

        if($tag)
        {
            if($tag->sub_account_id == $user->currentAccount->id)
            {   
                return view('contacts.edittag')->with(['user' => $user, 'main_user' => $mainUser, 'tag' => $tag]);
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with('error', "Tag not found");
    }

    public function updateTag(Request $request)
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

        $tag = Tag::find($data['tag_id']);
        if($tag)
        {
            if($tag->sub_account_id == $user->currentAccount->id)
            {   
                $tag->name = $data["name"];
                $tag->save();
                return back()->with('status', "Tag Updated Successfully");
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with('error', "Tag not found");
    }

    public function deleteTag($tag_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $tag = Tag::find($tag_id);

        if($tag)
        {
            if($tag->sub_account_id == $user->currentAccount->id)
            {   
                $tag->contacts()->detach();
                $tag->delete();
                return redirect('contacts_main')->with('status', 'Tag Deleted Successfully');
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with('error', "Tag not found");
    }

    public function removeTagFromContact($tag_id, $contact_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $tag = Tag::find($tag_id);

        if($tag)
        {
            if($tag->sub_account_id == $user->currentAccount->id)
            {   
                $tag->contacts()->detach($contact_id);
                return back()->with('status', 'Contact Removed Successfully');
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with('error', "Tag not found");
    }
}
