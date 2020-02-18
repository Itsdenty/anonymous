<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\UnsubscribeReason;

class UnsubscribePageController extends Controller
{
    //
    public function unsubscribePageSettings()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $num_of_contacts = $user->currentAccount->contacts->count();
        $num_of_unsubscribers = $user->currentAccount->contacts->where('unsubscribed', true)->count();

        $reasons = $user->currentAccount->unsubscribeReasons;

        $labels = [];
        $reason_values = [];
        foreach($reasons as $reason)
        {
            array_push($labels, $reason->title);
            array_push($reason_values, $reason->count);
        }

        return view('settings.unsubscribe_page_settings')->with(['user' => $user, 'main_user' => $mainUser, 'reasons' => $reasons, 'num_of_contacts' => $num_of_contacts, 'num_of_unsubscribers' => $num_of_unsubscribers, 'labels' => $labels, 'reason_values' => $reason_values]);
    }

    public function addReason(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();

        $unsubscrbe_reason = new UnsubscribeReason();
        $unsubscrbe_reason->title = $data['title'];
        $unsubscrbe_reason->sub_account_id = $user->currentAccount->id;
        $unsubscrbe_reason->save();

        return back()->with('status', "Reason Added Successfully");
    }

    public function updateReason(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();

        $unsubscrbe_reason = UnsubscribeReason::find($data['id']);
        if($unsubscrbe_reason)
        {
            if($unsubscrbe_reason->sub_account_id == $user->currentAccount->id)
            {
                $unsubscrbe_reason->title = $data['title'];
                $unsubscrbe_reason->save();
                return back()->with('status', "Unsubscribe Reason Updated Successfully");
            }
            return back()->with('error', 'Unauthorized Access');
        }
        return back()->with('error', 'Unsubscribe Reason not found');
    }

    public function updatePageContent(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();
        $sub_account = $user->currentAccount;
        $sub_account->unsubscribe_page_content = $data['unsubscribe_page_content'];
        $sub_account->save();

        return back()->with('status', 'Unsubscribe Page Content Updated Successfully');
    }

    public function deleteReason($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $unsubscrbe_reason = UnsubscribeReason::find($id);
        if($unsubscrbe_reason)
        {
            if($unsubscrbe_reason->sub_account_id == $user->currentAccount->id)
            {
                $unsubscrbe_reason->delete();
                return back()->with('status', "Unsubscribe Reason Deleted Successfully");
            }
            return back()->with('error', 'Unauthorized Access');
        }
        return back()->with('error', 'Unsubscribe Reason not found');
    }
}
