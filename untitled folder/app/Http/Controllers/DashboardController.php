<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Campaign;
use App\SmsCampaign;

class DashboardController extends Controller
{
    //
    public function viewDashboard()
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

        $campaigns = Campaign::where('sub_account_id', $user->currentAccount->id)->orderBy('id', 'desc')->take(5)->get();
        $sms_campaigns = SmsCampaign::where('sub_account_id', $user->currentAccount->id)->orderBy('id', 'desc')->take(5)->get();

        return view('dashboard.dashboard')->with(['user' => $user, 'main_user' => $mainUser, 'campaigns' => $campaigns, 'sms_campaigns' => $sms_campaigns, 'num_of_contacts' => $num_of_contacts, 'num_of_opens' => $num_of_opens, 'num_of_clicks' => $num_of_clicks, 'num_of_unsubscribers' => $num_of_unsubscribers]);
    }
}
