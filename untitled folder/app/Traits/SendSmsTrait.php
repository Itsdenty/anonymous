<?php

namespace App\Traits;

use Illuminate\Support\Carbon;
use Twilio\Rest\Client;
use App\SmsMmsIntegration;
use App\SmsCampaign;
use App\SmsCampaignLink;
use App\ContactSmsMessage;
use App\Activity;
use DateTime;

trait SendSmsTrait{
    public function twilioSendSms($campaign)
    {
        $this->storeCampaignLinks($campaign);
        
        $recepients = $campaign->contacts->where('unsubscribed', false);
        
        $sms_mms_integration = SmsMmsIntegration::find($campaign->sms_mms_integration_id);
        if($sms_mms_integration)
        {
            foreach($recepients as $receiver)
            {
                try
                {
                    $content  = $this->bladeCompile($this->replaceCampaignLink($campaign, $receiver->id), ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                }
                catch(Exception $e)
                {
                    return $e->getMessage();
                }

                // send the message
                $result = $this->sendSms($sms_mms_integration->account_sid, $sms_mms_integration->auth_token, $sms_mms_integration->twilio_number, $content, $campaign->image_url, $receiver->phone);

                if($result)
                {
                    $message_id = $result;
                    $campaign->contacts()->updateExistingPivot($receiver->id, ['sent' => true, 'message_id' => $message_id]);
                    $contact_sms_message = new ContactSmsMessage();
                    $contact_sms_message->content = $content;
                    $contact_sms_message->contact_id = $receiver->id;
                    $contact_sms_message->sms_mms_integration_id = $sms_mms_integration->id;
                    $contact_sms_message->save();

                    Activity::create(['content' => 'Received SMS/MMS "'. $campaign->title .'"', 'category' => 'sms', 'contact_id' => $receiver->id]);
                }
            }

            $this->sendNotification($campaign, $campaign->subaccount->user->email);
        }
    }

    private function bladeCompile($value, array $args = array())
    {
        $content = $value;

        foreach($args as $key=>$arg)
        {
            $content = str_replace('{'.$key.'}', $arg, $value);
        }
        return $content;
    }

    private function storeCampaignLinks($campaign)
    {
        // The Regular Expression filter
        $content = $campaign->content;
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        // $dom = new \DomDocument();
		// $dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
		// $anchors = $dom->getElementsByTagName('a');

        preg_match_all($reg_exUrl, $campaign->content, $links);

        foreach($links[0] as $link){
            if(!SmsCampaignLink::where('sms_campaign_id', $campaign->id)->where('actual_url', $link)->first())
            {
                $campaign_link = new SmsCampaignLink();
                $campaign_link->campaign_id = $campaign->id;
                $campaign_link->actual_url = $link;
                $campaign_link->replacement_url = $this->randomLink(10);
                $campaign_link->save();
            }
		} 
    }

    public function sendSms($account_sid, $auth_token, $twilio_number, $content, $image_url, $contact_number)
    {   
        try
        {
            $client = new Client($account_sid, $auth_token);
            if($image_url)
            {
                $picture_url = url($image_url);
                $message = $client->messages->create(
                    $contact_number,
                    array(
                        'from' => $twilio_number,
                        'body' => $content,
                        "mediaUrl" => array($picture_url)
                    )
                );
            }
            else
            {
                $message = $client->messages->create(
                    $contact_number,
                    array(
                        'from' => $twilio_number,
                        'body' => $content
                    )
                );
            }
            return $message->sid;
        }
        catch(\Exception $e)
        {
            return null;
        }

    }

    private function randomLink($length)
    {    
        do
        {
            $randomCode = str_random($length);
            $link = Link::where('replacement_url', $randomCode)->first();
        }
        while(!empty($link));        
        return $randomCode; 
    }

    private function replaceCampaignLink($campaign, $contact_id)
    {
        $content = $campaign->content;

        $links = $campaign->links;

        foreach($links as $link)
        {
            $content = str_replace("'".$link->actual_url."'", "'".url('smslink')."/".$link->replacement_url."/".$contact_id, $content."'");
            $content = str_replace('"'.$link->actual_url.'"', '"'.url('smslink').'/'.$link->replacement_url.'/'.$contact_id, $content.'"');
        }

        return $content;
    }

    private function sendNotification($campaign, $sender_email)
    {
        $data = [
            'campaign_id' => $campaign->id,
            'campaign_name' => $campaign->title,
            'created_at' => (new DateTime($campaign->created_at))->format('M j, Y H:i:s'),
            'sent_at' => (new DateTime($campaign->send_date))->format('M j, Y H:i:s'),
            'num_of_contacts' => $campaign->contacts->count(),
            'sender_email' => $sender_email
        ];
        
        \Mail::send('emails.smscampaignsent', $data, function($mail) use($data){
            $mail->to($data['sender_email']);
            $mail->from('support@pixlypro.com');
            $mail->subject('[Sendmunk] Your Campaign Has Been Sent');
        });
        return;
    }
}