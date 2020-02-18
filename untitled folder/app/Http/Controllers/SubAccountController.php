<?php

namespace App\Http\Controllers;

use App\User;
use App\SubAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubAccountController extends Controller
{
    public function index()
    {
        $user =  Auth::user();
        if($user->role_id == 3)
        {
            return view('errors.default');;
        }

        if(!$user->profile)
        {
            return redirect('settings')->with('status', "Please update your profile");
        }

        $mainUser = $user;
        $defaultAccount = $user->currentAccount->account_name;
        $userAccounts = $user->subAccounts;
        return view('settings.subaccount')->with(['user' => $user, 'current_account_name' => $defaultAccount, 'sub_accounts' => $userAccounts, 'main_user' => $mainUser]);
    }

    public function selectSubAccount(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        if($user->role_id == 3)
        {
            $user = $user->userLeader;
        }

        $subAccount = SubAccount::find($data['current_account_id']);
        $user->current_sub_account_id = $subAccount->id;
        $user->save();

        return back()->with(['status' => 'Active Account Changed']);
    }

    public function addSubAccount(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        if($user->role_id == 3)
        {
            return view('errors.default');
        }

        $subAccount = new SubAccount();
        $subAccount->account_name = $data['sub_account_name'];
        $subAccount->user_id = $user->id;

        $subAccount->save();

        return back()->with(['status' => $subAccount->account_name.' Sub Account has been created successfully']);
    }

    public function deleteSubAccount($id)
    {
        $user = Auth::user();
        if($user->role_id == 3)
        {
            return view('errors.default');;
        }
        
        $account = SubAccount::find($id);
        if($account->confirmed)
        {
            return back()->with(['error' => "You can't delete the default sub account"]);
        }
        if($user->current_sub_account_id == $account->id)
        {
            $user->current_sub_account_id = SubAccount::where('user_id', $user->id)->where('confirmed', 1)->first()->id;
            $user->save();
        }
        $account->delete();
        return back()->with(['status' => "Account Deleted Successfully"]);
    }
}