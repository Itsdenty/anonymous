<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\ContactAttribute;

class ContactAttributesController extends Controller
{
    //
    public function viewAttributes()
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

        $contact_attributes = $user->currentAccount->contactAttributes;

        return view('settings.contact_attributes')->with(['user' => $user, 'main_user' => $mainUser, 'contact_attributes' => $contact_attributes]);
    }

    public function createAttribute(Request $request)
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

        $data = $request->all();

        $contact_attribute = new ContactAttribute;
        $contact_attribute->name = str_replace(' ', '_', strtolower($data["name"]));
        $contact_attribute->type = $data["type"];
        $contact_attribute->sub_account_id = $user->currentAccount->id;
        $contact_attribute->save();

        return back()->with("status", "Contact Attributed Added Successfully");
    }

    public function deleteAttribute($attribute_id)
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

        $contact_attribute = ContactAttribute::find($attribute_id);
        if($contact_attribute)
        {
            if($contact_attribute->sub_account_id == $user->currentAccount->id)
            {
                $contact_attribute->delete();
                return back()->with('status', "Attribute Deleted Successfully");
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with('error', "Attribute not found");
    }

    public function updateAttribute(Request $request)
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

        $data = $request->all();

        $contact_attribute = ContactAttribute::find($data["attribute_id"]);
        if($contact_attribute)
        {
            if($contact_attribute->sub_account_id == $user->currentAccount->id)
            {
                $contact_attribute->name = str_replace(' ', '_', strtolower($data["name"]));
                $contact_attribute->type = $data["type"];
                $contact_attribute->save();

                return back()->with('status', "Attribute Updated Successfully");
            }
            return back()->with('error', "Authorized Access");
        }
        return back()->with('error', 'Attribute not found');
    }
}
