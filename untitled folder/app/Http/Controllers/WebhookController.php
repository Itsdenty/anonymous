<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\GeneralUnsubscriber;
use App\SmsMmsIntegration;
use App\ContactSmsMessage;
class WebhookController extends Controller
{
    //
    public function campaignHook(Request $request)
    {
        $data = $request->all();

        if(isset($data["message"]["headers"]["message-id"]))
        {
            $this->handleMailGun($data, 'campaign');
        }
        else if(isset($data["MessageID"]))
        {
            $this->handlePostMarkAndMailjet($data, 'campaign');
        }
        else if(isset($data[0]["sg_message_id"]))
        {
            $this->handleSendGrid($data, 'campaign');
        }
        else if(isset(($data[0]["msys"]["message_event"]["message_id"])))
        {
            $this->handleSparkPost($data, 'campaign');
        }
        else if(isset($data["Message-Id"]))
        {
            $this->handleSmtp2go($data, 'campaign');
        }
        else if(isset($data["commonHeaders"]["messageId"]))
        {
            $this->handleAmazonSes($data, 'campaign');
        }
    }

    public function sequenceEmailHook(Request $request)
    {
        $data = $request->all();

        if(isset($data["message"]["headers"]["message-id"]))
        {
            $this->handleMailGun($data, 'sequence');
        }
        else if(isset($data["MessageID"]))
        {
            $this->handlePostMarkAndMailjet($data, 'sequence');
        }
        else if(isset($data[0]["sg_message_id"]))
        {
            $this->handleSendGrid($data, 'sequence');
        }
        else if(isset(($data[0]["msys"]["message_event"]["message_id"])))
        {
            $this->handleSparkPost($data, 'sequence');
        }
        else if(isset($data["Message-Id"]))
        {
            $this->handleSmtp2go($data, 'sequence');
        }
        else if(isset($data["commonHeaders"]["messageId"]))
        {
            $this->handleAmazonSes($data, 'sequence');
        }
    }

    private function handleMailGun($request_data, $type)
    {
        $message_id = $request_data["message"]["headers"]["message-id"];
        if($type == 'campaign')
        {
            $object_contact = \DB::table('campaign_contact')->where('message_id', $message_id)->first();
        }
        else
        {
            $object_contact = \DB::table('contact_sequence_email')->where('message_id', $message_id)->first();
        }
        if($object_contact)
        {
            if(isset($request_data["event"]))
            {
                switch ($request_data["event"]) 
                {
                    case 'complained':
                        $object_contact->complained = true;
                        $object_contact->save();
                        $contact = Contact::find($object_contact->contact_id);
                        if($contact)
                        {
                            $contact->unsubscribed = true;
                            $contact->save();
                            $this->unsubscribeGeneral($contact);
                        }
                        break;
                    case 'rejected':
                        $object_contact->bounced = true;
                        $object_contact->save();
                        break;
                    case 'failed':
                        $object_contact->hard_bounced = true;
                        $object_contact->save();
                        if($request_data["severity"] == "permanent")
                        {
                            $contact = Contact::find($object_contact->contact_id);
                            if($contact)
                            {
                                $contact->unsubscribed = true;
                                $contact->save();
                                $this->unsubscribeGeneral($contact);
                            }
                            break;
                        }
                        break;
                }
            }
        }
    }

    private function handlePostMarkAndMailjet($request_data, $type)
    {
        $message_id = $request_data["MessageID"];
        if($type == 'campaign')
        {
            $object_contact = \DB::table('campaign_contact')->where('message_id', $message_id)->first();
        }
        else
        {
            $object_contact = \DB::table('contact_sequence_email')->where('message_id', $message_id)->first();
        }
        if($object_contact)
        {
            // For PostMark
            if(isset($request_data["RecordType"]))
            {
                switch ($request_data["RecordType"]) 
                {
                    case 'SpamComplaint':
                        $object_contact->complained = true;
                        $object_contact->save();
                        $contact = Contact::find($object_contact->contact_id);
                        if($contact)
                        {
                            $contact->unsubscribed = true;
                            $contact->save();
                            $this->unsubscribeGeneral($contact);
                        }
                        break;
                    case 'Bounce':
                        $object_contact->hard_bounced = true;
                        $object_contact->save();
                        $contact = Contact::find($object_contact->contact_id);
                        if($contact)
                        {
                            $contact->unsubscribed = true;
                            $contact->save();
                            $this->unsubscribeGeneral($contact);
                        }               
                        break;
                }
            }
            // For Mailjet
            else if(isset($request_data["event"]))
            {
                switch ($request_data["event"]) 
                {
                    case 'spam':
                        $object_contact->complained = true;
                        $object_contact->save();
                        $contact = Contact::find($object_contact->contact_id);
                        if($contact)
                        {
                            $contact->unsubscribed = true;
                            $contact->save();
                            $this->unsubscribeGeneral($contact);
                        } 
                        break;
                    case 'bounce':
                        $object_contact->bounced = true;
                        $object_contact->save();
                        break;
                    case 'blocked':
                        $object_contact->hard_bounced = true;
                        $object_contact->save();
                        $contact = Contact::find($object_contact->contact_id);
                        if($contact)
                        {
                            $contact->unsubscribed = true;
                            $contact->save();
                            $this->unsubscribeGeneral($contact);
                        }                 
                        break;
                }
            }
        }
    }

    private function handleSendGrid($request_data, $type)
    {
        foreach($request_data as $event)
        {
            $message_id = explode('.', $event["sg_message_id"])[0];
            if($type == 'campaign')
            {
                $object_contact = \DB::table('campaign_contact')->where('message_id', $message_id)->first();
            }
            else
            {
                $object_contact = \DB::table('contact_sequence_email')->where('message_id', $message_id)->first();
            }
            if($object_contact)
            {
                if(isset($event["event"]))
                {
                    switch ($event["event"]) 
                    {
                        case 'spamreport':
                            $object_contact->complained = true;
                            $object_contact->save();
                            $contact = Contact::find($object_contact->contact_id);
                            if($contact)
                            {
                                $contact->unsubscribed = true;
                                $contact->save();
                                $this->unsubscribeGeneral($contact);
                            } 
                            break;
                        case 'deferred':
                            $object_contact->bounced = true;
                            $object_contact->save();
                            break;
                        case 'bounce':
                            $object_contact->hard_bounced = true;
                            $object_contact->save();
                            $contact = Contact::find($object_contact->contact_id);
                            if($contact)
                            {
                                $contact->unsubscribed = true;
                                $contact->save();
                                $this->unsubscribeGeneral($contact);
                            } 
                            break;
                    }
                }
            }
        }
    }

    private function handleSparkPost($request_data, $type)
    {
        $message_id = $request_data[0]["msys"]["message_event"]["message_id"];
        if($type == 'campaign')
        {
            $object_contact = \DB::table('campaign_contact')->where('message_id', $message_id)->first();
        }
        else
        {
            $object_contact = \DB::table('contact_sequence_email')->where('message_id', $message_id)->first();
        }
        if($object_contact)
        {
            if(isset($request_data[0]["msys"]["message_event"]["type"]))
            {
                switch ($request_data[0]["msys"]["message_event"]["type"]) 
                {
                    case 'spam_complaint':
                        $object_contact->complained = true;
                        $object_contact->save();
                        $contact = Contact::find($object_contact->contact_id);
                        if($contact)
                        {
                            $contact->unsubscribed = true;
                            $contact->save();
                            $this->unsubscribeGeneral($contact);
                        } 
                        break;
                    case 'out_of_band':
                    case 'policy_rejection':
                        $object_contact->bounced = true;
                        $object_contact->save();
                        break;
                    case 'bounce':
                        $object_contact->hard_bounced = true;
                        $object_contact->save();
                        $contact = Contact::find($object_contact->contact_id);
                        if($contact)
                        {
                            $contact->unsubscribed = true;
                            $contact->save();
                            $this->unsubscribeGeneral($contact);
                        }                
                        break;
                }
            }
        }
    }

    private function handleSmtp2go($request_data, $type)
    {
        $message_id = $request_data["Message-Id"];
        if($type == 'campaign')
        {
            $object_contact = \DB::table('campaign_contact')->where('message_id', $message_id)->first();
        }
        else
        {
            $object_contact = \DB::table('contact_sequence_email')->where('message_id', $message_id)->first();
        }
        if($object_contact)
        {
            if(isset($request_data["event"]))
            {
                switch ($request_data["event"]) 
                {
                    case 'spam':
                        $object_contact->complained = true;
                        $object_contact->save();
                        $contact = Contact::find($object_contact->contact_id);
                        if($contact)
                        {
                            $contact->unsubscribed = true;
                            $contact->save();
                            $this->unsubscribeGeneral($contact);
                        } 
                        break;
                    case 'bounce':
                        if($request_data["bounce"] == 'soft')
                        {
                            $object_contact->bounced = true;
                            $object_contact->save();
                        }
                        else if($request_data["bounce"] == 'hard')
                        {
                            $object_contact->hard_bounced = true;
                            $object_contact->save();
                            $contact = Contact::find($object_contact->contact_id);
                            if($contact)
                            {
                                $contact->unsubscribed = true;
                                $contact->save();
                                $this->unsubscribeGeneral($contact);
                            } 
                        }               
                        break;
                    case 'reject':
                        $object_contact->hard_bounced = true;
                        $object_contact->save();
                        if($request_data["bounce"] == 'hard')
                        {
                            $contact = Contact::find($object_contact->contact_id);
                            if($contact)
                            {
                                $contact->unsubscribed = true;
                                $contact->save();
                                $this->unsubscribeGeneral($contact);
                            } 
                        }  
                        break;
                }
            }
        }
    }

    private function handleAmazonSes($request_data, $type)
    {
        $message_id = $request_data["commonHeaders"]["messageId"];
        if($type == 'campaign')
        {
            $object_contact = \DB::table('campaign_contact')->where('message_id', $message_id)->first();
        }
        else
        {
            $object_contact = \DB::table('contact_sequence_email')->where('message_id', $message_id)->first();
        }
        if($object_contact)
        {
            if(isset($request_data["bounceType"]))
            {
                switch ($request_data["bounceType"]) 
                {
                    case 'Transient':
                        $object_contact->bounced = true;
                        $object_contact->save();
                        break;
                    case 'Permanent':
                        $object_contact->hard_bounced = true;
                        $object_contact->save();
                        $contact = Contact::find($object_contact->contact_id);
                        if($contact)
                        {
                            $contact->unsubscribed = true;
                            $contact->save();
                            $this->unsubscribeGeneral($contact);
                        } 
                        break;
                }
            }
            else if(isset($request_data["complainedRecipients"]))
            {
                $object_contact->complained = true;
                $object_contact->save();
                $contact = Contact::find($object_contact->contact_id);
                if($contact)
                {
                    $contact->unsubscribed = true;
                    $contact->save();
                    $this->unsubscribeGeneral($contact);
                } 
            }
        }
    }

    private function unsubscribeGeneral($contact)
    {
        $user = $contact->subaccount->user;
        $general_unsubscriber = GeneralUnsubscriber::where('email', $contact->email)->where('user_id', $user->id)->first();
        if(!$general_unsubscriber)
        {
            $general_unsubscriber = new GeneralUnsubscriber();
            $general_unsubscriber->email = $contact->email;
            $general_unsubscriber->user_id = $user->id;
            $general_unsubscriber->save();
        }
    }

    public function receiveMessage(Request $request)
    {
        $data = $request->all();
        $account_sid = $data['AccountSid'];

        $sms_mms_integrations = SmsMmsIntegration::where("account_sid", $account_sid)->get();
        foreach($sms_mms_integrations as $integration)
        {
            $contacts = $integration->subaccount->contacts->where('phone', $data['From']);
            foreach($contacts as $contact)
            {
                $contact_sms_message = new ContactSmsMessage();
                $contact_sms_message->content = $data['Body'];
                $contact_sms_message->contact_id = $contact->id;
                $contact_sms_message->sms_mms_integration_id = $integration->id;
                $contact_sms_message->save();
            }
        }
    }
}
