<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Twilio\Rest\Client;
use App\SmsMmsIntegration;

class SmsMmsController extends Controller
{
    //
    public function viewSmsMmsIntegrations()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $sms_mms_integrations = $user->currentAccount->smsMmsIntegrations;

        return view('settings.sms_mms')->with(['user'=> $user, 'main_user' => $mainUser, 'sms_mms_integrations' => $sms_mms_integrations]);
    }

    public function createSmsMssIntegration(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data  = $request->all();

        $account_sid = $data['account_sid'];
        $auth_token = $data['auth_token'];
        $twilio_number = $data['twilio_number'];
        
        try
        {
            $client = new Client($account_sid, $auth_token);
            $message = $client->messages->create(
                '+2348061270775',
                array(
                    'from' => $twilio_number,
                    'body' => 'Verify Twilio Integration'
                )
            );
            if($message->sid)
            {
                $message = "SMS/MMS settings Added successfully";
                if(isset($data['sms_mms_id']))
                {
                    $sms_mms_integration = SmsMmsIntegration::find($data['sms_mms_id']);
                    if($sms_mms_integration)
                    {
                        if($sms_mms_integration->sub_account_id == $user->currentAccount->id)
                        {
                            $message = "SMS/MMS settings Updated Successfully";
                        }
                        else
                        {
                            return back()->with('error', 'Unauthorized Access');
                        }
                    }
                    else
                    {
                        return back()->with('error', "Settings not found");
                    }
                }
                else
                {
                    $sms_mms_integration = new SmsMmsIntegration();
                }
                $sms_mms_integration->account_sid = $account_sid;
                $sms_mms_integration->auth_token = $auth_token;
                $sms_mms_integration->twilio_number = $twilio_number;
                if(!$user->currentAccount->smsMmsIntegrations->where('current_settings', true)->first())
                {
                    $sms_mms_integration->current_settings = true;
                }
                $sms_mms_integration->sub_account_id = $user->currentAccount->id;
                $sms_mms_integration->save();

                return back()->with('status', $message);
            }
        }
        catch(\Exception $e)
        {
            return back()->with('error', $e->getMessage());
        }

    }

    public function deleteSmsMms($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $sms_mms_integration = SmsMmsIntegration::find($id);
        if($sms_mms_integration)
        {
            if($sms_mms_integration->sub_account_id == $user->currentAccount->id)
            {
                $sms_mms_integration->delete();
                return back()->with('status', "SMS/MMS settings deleted");
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with('error', "Settings not found");
    }
}
