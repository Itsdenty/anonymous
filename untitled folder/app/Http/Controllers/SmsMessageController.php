<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Contact;
use App\SmsMmsIntegration;
use App\Traits\SendSmsTrait;
use App\ContactSmsMessage;

class SmsMessageController extends Controller
{
    use SendSmsTrait;

    public function viewSmsMessages()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $contacts = $user->currentAccount->contacts->where('phone', '!=', null);
        $integrations = $user->currentAccount->smsMmsIntegrations;

        return view('sms_mms.messages.messages')->with(['user' => $user, 'main_user' => $mainUser, 'contacts' => $contacts, 'integrations' => $integrations]);
    }

    public function getMessages($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $contact = Contact::find($id);
        if($contact)
        {
            ContactSmsMessage::where('contact_id', $contact->id)->where('sms_mms_integration_id', $user->currentAccount->smsMmsIntegrations->where('current_settings', true)->first()->id)->update(['unread' => false]);
            return $contact->contactSmsMessages->where('sms_mms_integration_id', $user->currentAccount->smsMmsIntegrations->where('current_settings', true)->first()->id);
        }
        return;
    }

    public function setActiveIntegration(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();

        SmsMmsIntegration::where('sub_account_id', $user->currentAccount->id)->update(['current_settings' => false]);
        SmsMmsIntegration::where('sub_account_id', $user->currentAccount->id)->where('id', $data['current_integration_id'])->update(['current_settings' => true]);

        return;
    }

    public function sendContactMessage(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();
        
        $contact = Contact::find($data["contact_id"]);
        $sms_mms_integration = SmsMmsIntegration::where('sub_account_id', $user->currentAccount->id)->where('current_settings', true)->first();
        if($contact && $sms_mms_integration)
        {
            $result = $this->sendSms($sms_mms_integration->account_sid, $sms_mms_integration->auth_token, $sms_mms_integration->twilio_number, $data["message"], null, $contact->phone);
            if($result)
            {
                $contact_sms_message = new ContactSmsMessage();
                $contact_sms_message->content = $data["message"];
                $contact_sms_message->contact_id = $contact->id;
                $contact_sms_message->sms_mms_integration_id = $sms_mms_integration->id;
                $contact_sms_message->save();
                return response()->json(['status'=>'Message Sent'], 200);
            }
        }
        return response()->json(['status'=>'An Error Occured'], 404);
    }
}
