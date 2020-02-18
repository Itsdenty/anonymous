<?php

namespace App\Traits;

use Illuminate\Support\Carbon;
use App\Campaign;
use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use Swift_Plugins_AntiFloodPlugin;
use App\Smtp;
use App\MailApi;
use App\FromReplyEmail;
use App\Link;
use App\Activity;
use DateTime;
use Image;
use SendGrid;

trait SendMailTrait{
    public function smtpSendMail($campaign)
    {
        $this->storeCampaignLinks($campaign);

        $this->replaceImagesWithLinks($campaign);
        
        $recepients = $campaign->contacts->where('unsubscribed', false);
        
        $smtp = Smtp::find($campaign->smtp_id);
        if($smtp)
        {
            // Sender Email
            $sender_email = FromReplyEmail::find($campaign->from_reply_id)->email;
            
            // Create the Transport
            if($smtp->encryption == 'null')
            {
                $transport = (new Swift_SmtpTransport($smtp->server, $smtp->port))
                ->setUsername($smtp->user)
                ->setPassword($smtp->password)
                ;
            }
            else
            {
                $transport = (new Swift_SmtpTransport($smtp->server, $smtp->port, $smtp->encryption))
                ->setUsername($smtp->user)
                ->setPassword($smtp->password)
                ;
            }

            // Create the Mailer using your created Transport
            $mailer = new Swift_Mailer($transport);

            if($smtp->is_limited == true)
            {
                // And specify a time in seconds to pause
                $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(intval($smtp->sending_limit), $smtp->time_in_seconds));
            }

            // Create a message

            // Check if AB Test is enabled
            if($campaign->ab_test && $campaign->subject_b)
            {
                $number_of_recepients = $recepients->count();
                // get 30 percent of recepients
                $break_point = intval(30 / 100 * $number_of_recepients);
                
                $counter = 0;

                foreach($recepients as $receiver)
                {
                    if($counter == $break_point)
                    {
                        break;
                    }
                    try
                    {
                        $content  = $this->bladeCompile($this->replaceCampaignLink($campaign, $receiver->id), ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                        if($counter % 2 == 0)
                        {
                            $subject  = $this->bladeCompile($campaign->subject_a, ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                            $campaign->contacts()->updateExistingPivot($receiver->id, ['campaign_subject' => $campaign->subject_a]);
                        }
                        else
                        {
                            $subject  = $this->bladeCompile($campaign->subject_b, ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                            $campaign->contacts()->updateExistingPivot($receiver->id, ['campaign_subject' => $campaign->subject_b]);
                        }
                    }
                    catch(Exception $e)
                    {
                        return back()->with('error', $e->getMessage());
                    }

                    $message = (new Swift_Message($subject))
                    ->setFrom([$sender_email])
                    ->setTo([$receiver->email])
                    ->setBody($content.'<img src="' . url('trackopened'). '/' . $campaign->id . '/'. $receiver->id .'" alt="" width="1" height="1" border="0" />'.'<div style="text-align: center;"><br/><br/><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->address.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->city.' '.$campaign->subaccount->user->profile->state.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->country->name.'</p><br/><a href="'. url('unsubscribe'). '/' . $campaign->id . '/'. $receiver->id .'">Unsubscribe</a> | <a href="'. url('changesubscriber'). '/' . $campaign->id . '/'. $receiver->id .'">Manage my subscription</a></div>','text/html');

                    $headers = $message->getHeaders()->get('Message-ID');
                    $prefix = 'Message-ID: ';
                    $message_id = ''.$headers;
                    if (substr($message_id, 0, strlen($prefix)) == $prefix) {
                        $message_id = substr($message_id, strlen($prefix));
                    } 

                    // Send the message
                    $campaign->contacts()->updateExistingPivot($receiver->id, ['sent' => true, 'message_id' => $message_id]);
                    Activity::create(['content' => 'Received email "'. $campaign->title .'"', 'category' => 'sent_emails', 'contact_id' => $receiver->id]);

                    try
                    {
                        $result = $mailer->send($message);
                    }
                    catch(\Swift_TransportException $e)
                    {
                        $campaign->contacts()->updateExistingPivot($receiver->id, ['bounced' => true]);
                        Activity::create(['content' => 'Bounce email "'. $campaign->title .'"', 'category' => 'bounces', 'contact_id' => $receiver->id]);
                    }
                    catch(Exception $e)
                    {
                        $campaign->contacts()->updateExistingPivot($receiver->id, ['bounced' => true]);
                        Activity::create(['content' => 'Bounced email "'. $campaign->title .'"', 'category' => 'bounces', 'contact_id' => $receiver->id]);
                    }
                }

                $now = date("Y-m-d H:i", strtotime(Carbon::now())).":00";
                $campaign->actual_send_date = date('Y-m-d H:i', (strtotime($now) - ($campaign->subaccount->user->profile->timezone->offset * 60 * 60) + (30 * 60)));
                $campaign->status = 'progress';
                $campaign->save();
            }
            else
            {
                foreach($recepients as $receiver)
                {
                    try
                    {
                        $content  = $this->bladeCompile($this->replaceCampaignLink($campaign, $receiver->id), ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                        $subject  = $this->bladeCompile($campaign->subject_a, ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                    }
                    catch(Exception $e)
                    {
                        return back()->with('error', $e->getMessage());
                    }

                    $message = (new Swift_Message($subject))
                    ->setFrom([$sender_email])
                    ->setTo([$receiver->email])
                    ->setBody($content.'<img src="' . url('trackopened'). '/' . $campaign->id . '/'. $receiver->id .'" alt="" width="1" height="1" border="0" />'.'<div style="text-align: center;"><br/><br/><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->address.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->city.' '.$campaign->subaccount->user->profile->state.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->country->name.'</p><br/><a href="'. url('unsubscribe'). '/' . $campaign->id . '/'. $receiver->id .'">Unsubscribe</a> | <a href="'. url('changesubscriber'). '/' . $campaign->id . '/'. $receiver->id .'">Manage my subscription</a></div>','text/html');

                    $headers = $message->getHeaders()->get('Message-ID');
                    $prefix = 'Message-ID: ';
                    $message_id = ''.$headers;
                    if (substr($message_id, 0, strlen($prefix)) == $prefix) {
                        $message_id = substr($message_id, strlen($prefix));
                    } 

                    // Send the message
                    $campaign->contacts()->updateExistingPivot($receiver->id, ['sent' => true, 'message_id' => $message_id]);
                    Activity::create(['content' => 'Received email "'. $campaign->title .'"', 'category' => 'sent_emails', 'contact_id' => $receiver->id]);
                    try
                    {
                        $result = $mailer->send($message);
                    }
                    catch(\Swift_TransportException $e)
                    {
                        $campaign->contacts()->updateExistingPivot($receiver->id, ['bounced' => true]);
                        Activity::create(['content' => 'Bounced email "'. $campaign->title .'"', 'category' => 'bounces', 'contact_id' => $receiver->id]);
                    }
                    catch(Exception $e)
                    {
                        $campaign->contacts()->updateExistingPivot($receiver->id, ['bounced' => true]);
                        Activity::create(['content' => 'Bounced email "'. $campaign->title .'"', 'category' => 'bounces', 'contact_id' => $receiver->id]);
                    }


                    $campaign->contacts()->updateExistingPivot($receiver->id, ['sent' => true, 'campaign_subject' => $campaign->subject_a, 'message_id' => $message_id]);
                }
            }

            $this->sendNotification($campaign, $sender_email);
        }
    }

    public function mailgunSendMail($campaign)
    {
        $this->storeCampaignLinks($campaign);

        $this->replaceImagesWithLinks($campaign);
        
        $recepients = $campaign->contacts->where('unsubscribed', false);
        
        $mailApi = MailApi::find($campaign->mail_api_id);
        if($mailApi && $mailApi->api_channel_id == 2)
        {
            // Sender Email
            $sender_email = FromReplyEmail::find($campaign->from_reply_id)->email;

            // Check if AB Test is enabled
            if($campaign->ab_test && $campaign->subject_b)
            {
                $number_of_recepients = $recepients->count();
                // get 30 percent of recepients
                $break_point = intval(30 / 100 * $number_of_recepients);
                
                $counter = 0;

                foreach($recepients as $receiver)
                {
                    if($counter == $break_point)
                    {
                        break;
                    }
                    try
                    {
                        $content  = $this->bladeCompile($this->replaceCampaignLink($campaign, $receiver->id), ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                        if($counter % 2 == 0)
                        {
                            $subject  = $this->bladeCompile($campaign->subject_a, ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                            $campaign->contacts()->updateExistingPivot($receiver->id, ['campaign_subject' => $campaign->subject_a]);
                        }
                        else
                        {
                            $subject  = $this->bladeCompile($campaign->subject_b, ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                            $campaign->contacts()->updateExistingPivot($receiver->id, ['campaign_subject' => $campaign->subject_b]);
                        }
                    }
                    catch(Exception $e)
                    {
                        return $e->getMessage();
                    }

                    $mail_content = $content.'<img src="' . url('trackopened'). '/' . $campaign->id . '/'. $receiver->id .'" alt="" width="1" height="1" border="0" />'.'<div style="text-align: center;"><br/><br/><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->address.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->city.' '.$campaign->subaccount->user->profile->state.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->country->name.'</p><br/><a href="'. url('unsubscribe'). '/' . $campaign->id . '/'. $receiver->id .'">Unsubscribe</a> | <a href="'. url('changesubscriber'). '/' . $campaign->id . '/'. $receiver->id .'">Manage my subscription</a></div>';

                    // send the message
                    $result = $this->mailgunSend($mailApi->api_key, $mailApi->domain, $sender_email, $receiver->email, $subject,$mail_content);
                    Activity::create(['content' => 'Received email "'. $campaign->title .'"', 'category' => 'sent_emails', 'contact_id' => $receiver->id]);

                    $message_id = null;
                    $result_array = json_decode($result, true);
                    if(isset($result_array['id']))
                    {
                        $message_id = $result_array['id'];
                    }
                    else
                    {
                        $campaign->contacts()->updateExistingPivot($receiver->id, ['bounced' => true]);
                        Activity::create(['content' => 'Bounced email "'. $campaign->title .'"', 'category' => 'bounces', 'contact_id' => $receiver->id]);
                    }

                    $campaign->contacts()->updateExistingPivot($receiver->id, ['sent' => true, 'message_id' => $message_id]);
                }

                $now = date("Y-m-d H:i", strtotime(Carbon::now())).":00";
                $campaign->actual_send_date = date('Y-m-d H:i', (strtotime($now) - ($campaign->subaccount->user->profile->timezone->offset * 60 * 60) + (30 * 60)));
                $campaign->status = 'progress';
                $campaign->save();
            }
            else
            {
                foreach($recepients as $receiver)
                {
                    try
                    {
                        $content  = $this->bladeCompile($this->replaceCampaignLink($campaign, $receiver->id), ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                        $subject  = $this->bladeCompile($campaign->subject_a, ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                    }
                    catch(Exception $e)
                    {
                        return $e->getMessage();
                    }

                    $mail_content = $content.'<img src="' . url('trackopened'). '/' . $campaign->id . '/'. $receiver->id .'" alt="" width="1" height="1" border="0" />'.'<div style="text-align: center;"><br/><br/><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->address.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->city.' '.$campaign->subaccount->user->profile->state.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->country->name.'</p><br/><a href="'. url('unsubscribe'). '/' . $campaign->id . '/'. $receiver->id .'">Unsubscribe</a> | <a href="'. url('changesubscriber'). '/' . $campaign->id . '/'. $receiver->id .'">Manage my subscription</a></div>';

                    // send the message
                    $result = $this->mailgunSend($mailApi->api_key, $mailApi->domain, $sender_email, $receiver->email, $subject,$mail_content);
                    Activity::create(['content' => 'Received email "'. $campaign->title .'"', 'category' => 'sent_emails', 'contact_id' => $receiver->id]);

                    $message_id = null;
                    $result_array = json_decode($result, true);
                    if(isset($result_array['id']))
                    {
                        $message_id = $result_array['id'];
                    }
                    else
                    {
                        $campaign->contacts()->updateExistingPivot($receiver->id, ['bounced' => true]);
                        Activity::create(['content' => 'Bounced email "'. $campaign->title .'"', 'category' => 'bounces', 'contact_id' => $receiver->id]);
                    }

                    $campaign->contacts()->updateExistingPivot($receiver->id, ['sent' => true, 'campaign_subject' => $campaign->subject_a, 'message_id' => $message_id]);
                }
            }

            $this->sendNotification($campaign, $sender_email);
        }
    }

    public function sendgridSendMail($campaign)
    {
        $this->storeCampaignLinks($campaign);

        $this->replaceImagesWithLinks($campaign);
        
        $recepients = $campaign->contacts ->where('unsubscribed', false);
        
        $mailApi = MailApi::find($campaign->mail_api_id);
        if($mailApi && $mailApi->api_channel_id == 1)
        {
            // Sender Email
            $sender_email = FromReplyEmail::find($campaign->from_reply_id)->email;

            // Check if AB Test is enabled
            if($campaign->ab_test && $campaign->subject_b)
            {
                $number_of_recepients = $recepients->count();
                // get 30 percent of recepients
                $break_point = intval(30 / 100 * $number_of_recepients);
                
                $counter = 0;

                foreach($recepients as $receiver)
                {
                    if($counter == $break_point)
                    {
                        break;
                    }
                    try
                    {
                        $content  = $this->bladeCompile($this->replaceCampaignLink($campaign, $receiver->id), ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                        if($counter % 2 == 0)
                        {
                            $subject  = $this->bladeCompile($campaign->subject_a, ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                            $campaign->contacts()->updateExistingPivot($receiver->id, ['campaign_subject' => $campaign->subject_a]);
                        }
                        else
                        {
                            $subject  = $this->bladeCompile($campaign->subject_b, ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                            $campaign->contacts()->updateExistingPivot($receiver->id, ['campaign_subject' => $campaign->subject_b]);
                        }
                    }
                    catch(Exception $e)
                    {
                        return $e->getMessage();
                    }

                    $mail_content = $content.'<img src="' . url('trackopened'). '/' . $campaign->id . '/'. $receiver->id .'" alt="" width="1" height="1" border="0" />'.'<div style="text-align: center;"><br/><br/><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->address.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->city.' '.$campaign->subaccount->user->profile->state.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->country->name.'</p><br/><a href="'. url('unsubscribe'). '/' . $campaign->id . '/'. $receiver->id .'">Unsubscribe</a> | <a href="'. url('changesubscriber'). '/' . $campaign->id . '/'. $receiver->id .'">Manage my subscription</a></div>';

                    // send the message
                    $result = $this->sendgridSend($mailApi->api_key, $sender_email, $receiver->email, $subject,$mail_content);
                    Activity::create(['content' => 'Received email "'. $campaign->title .'"', 'category' => 'sent_emails', 'contact_id' => $receiver->id]);

                    if(!$result)
                    {
                        $campaign->contacts()->updateExistingPivot($receiver->id, ['bounced' => true]);
                        Activity::create(['content' => 'Bounced email "'. $campaign->title .'"', 'category' => 'bounces', 'contact_id' => $receiver->id]);
                    }

                    $campaign->contacts()->updateExistingPivot($receiver->id, ['sent' => true, 'message_id' => $result]);
                }

                $now = date("Y-m-d H:i", strtotime(Carbon::now())).":00";
                $campaign->actual_send_date = date('Y-m-d H:i', (strtotime($now) - ($campaign->subaccount->user->profile->timezone->offset * 60 * 60) + (30 * 60)));
                $campaign->status = 'progress';
                $campaign->save();
            }
            else
            {
                foreach($recepients as $receiver)
                {
                    try
                    {
                        $content  = $this->bladeCompile($this->replaceCampaignLink($campaign, $receiver->id), ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                        $subject  = $this->bladeCompile($campaign->subject_a, ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                    }
                    catch(Exception $e)
                    {
                        return $e->getMessage();
                    }

                    $mail_content = $content.'<img src="' . url('trackopened'). '/' . $campaign->id . '/'. $receiver->id .'" alt="" width="1" height="1" border="0" />'.'<div style="text-align: center;"><br/><br/><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->address.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->city.' '.$campaign->subaccount->user->profile->state.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->country->name.'</p><br/><a href="'. url('unsubscribe'). '/' . $campaign->id . '/'. $receiver->id .'">Unsubscribe</a> | <a href="'. url('changesubscriber'). '/' . $campaign->id . '/'. $receiver->id .'">Manage my subscription</a></div>';

                    // send the message
                    $result = $this->sendgridSend($mailApi->api_key, $sender_email, $receiver->email, $subject,$mail_content);
                    Activity::create(['content' => 'Received email "'. $campaign->title .'"', 'category' => 'sent_emails', 'contact_id' => $receiver->id]);

                    if(!$result)
                    {
                        $campaign->contacts()->updateExistingPivot($receiver->id, ['bounced' => true]);
                        Activity::create(['content' => 'Bounced email "'. $campaign->title .'"', 'category' => 'bounces', 'contact_id' => $receiver->id]);
                    }

                    $campaign->contacts()->updateExistingPivot($receiver->id, ['sent' => true, 'campaign_subject' => $campaign->subject_a, 'message_id' => $result]);
                }
            }

            $this->sendNotification($campaign, $sender_email);
        }
    }

    private function bladeCompile($value, array $args = array())
    {
        $generated = \Blade::compileString($value);

        ob_start() and extract($args, EXTR_SKIP);

        try
        {
            @eval('?>'.$generated);
        }
        catch (Exception $e)
        {
            ob_get_clean(); throw $e;
        }

        $content = ob_get_clean();

        return $content;
    }

    private function  mailgunSend($api_key, $domain, $sender_mail, $receiver_email, $subject, $msg) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:'.$api_key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/'.$domain.'/messages');
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'from' => $sender_mail,
            'to' => $receiver_email,
            'subject' => $subject,
            'html' => $msg
        ));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    private function sendgridSend($api_key, $sender_email, $receiver_email, $subject, $msg)
    {
        $email = new SendGrid\Mail\Mail(); 
        $email->setFrom($sender_email);
        $email->setSubject($subject);
        $email->addTo($receiver_email);
        $email->addContent("text/plain", \strip_tags($msg));
        $email->addContent(
            "text/html", $msg
        );
        $sendgrid = new SendGrid($api_key);
        try 
        {
            $response = $sendgrid->send($email);            
            $headers = $response->headers();
            foreach($headers as $message_id)
            {
                $prefix = 'X-Message-Id: ';
                if (substr($message_id, 0, strlen($prefix)) == $prefix) {
                    $message_id = substr($message_id, strlen($prefix));
                    return $message_id;
                }
            }
        } catch (Exception $e) {
           return null;
        }
        return null;
    }

    private function storeCampaignLinks($campaign)
    {
        // The Regular Expression filter
        $content = $campaign->content;
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        $dom = new \DomDocument();
		$dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
		$anchors = $dom->getElementsByTagName('a');

        preg_match_all($reg_exUrl, $campaign->content, $links);

        foreach($anchors as $anchor){
			$link = $anchor->getAttribute('href');
			
            if(preg_match($reg_exUrl, $link))
            {
                if(!Link::where('campaign_id', $campaign->id)->where('actual_url', $link)->first())
                {
                    $campaign_link = new Link();
                    $campaign_link->campaign_id = $campaign->id;
                    $campaign_link->actual_url = $link;
                    $campaign_link->replacement_url = $this->randomLink(10);
                    $campaign_link->save();
                }
			}
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
            $content = str_replace("'".$link->actual_url."'", "'".url('link')."/".$link->replacement_url."/".$contact_id, $content."'");
            $content = str_replace('"'.$link->actual_url.'"', '"'.url('link').'/'.$link->replacement_url.'/'.$contact_id, $content.'"');
        }

        return $content;
    }

    public function sendSender($campaign)
    {
        $smtp = Smtp::find($campaign->smtp_id);
        $mailApi = MailApi::find($campaign->mail_api_id);
        // Sender Email
        $sender_email = FromReplyEmail::find($campaign->from_reply_id)->email;

        if($smtp)
        {            
            // Create the Transport
            $transport = (new Swift_SmtpTransport($smtp->server, $smtp->port))
            ->setUsername($smtp->user)
            ->setPassword($smtp->password)
            ;

            // Create the Mailer using your created Transport
            $mailer = new Swift_Mailer($transport);

            if($smtp->server == 'smtp.mailtrap.io')
            {
                // Use AntiFlood to re-connect after 3 emails
                $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(3));

                // And specify a time in seconds to pause for (1 sec)
                $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(3, 1));
            }

            try
            {
                $content  = $this->bladeCompile($campaign->content, ['subscriber_name' => $campaign->subaccount->user->name, 'subscriber_email' => $sender_email]);
                $subject  = $this->bladeCompile($campaign->subject_a, ['subscriber_name' => $campaign->subaccount->user->name, 'subscriber_email' => $sender_email]);
            }
            catch(Exception $e)
            {
                return back()->with('error', $e->getMessage());
            }

            $message = (new Swift_Message($subject))
            ->setFrom([$sender_email])
            ->setTo([$sender_email])
            ->setBody($content.'<div style="text-align: center;"><br/><br/><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->address.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->city.' '.$campaign->subaccount->user->profile->state.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->country->name.'</p><br/></div>','text/html');

            // Send the message
            $result = $mailer->send($message);


        }
        else if($mailApi && $mailApi->api_channel_id == 2)
        {
            try
            {
                $content  = $this->bladeCompile($campaign->content, ['subscriber_name' => $campaign->subaccount->user->name, 'subscriber_email' => $sender_email]);
                $subject  = $this->bladeCompile($campaign->subject_a, ['subscriber_name' => $campaign->subaccount->user->name, 'subscriber_email' => $sender_email]);
            }
            catch(Exception $e)
            {
                return $e->getMessage();
            }

            $mail_content = $content.'<div style="text-align: center;"><br/><br/><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->address.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->city.' '.$campaign->subaccount->user->profile->state.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->country->name.'</p><br/></div>';

            // send the message
            $result = $this->mailgunSend($mailApi->api_key, $mailApi->domain, $sender_email, $sender_email, $subject,$mail_content);
        }
        else if($mailApi && $mailApi->api_channel_id == 1)
        {
            try
            {
                $content  = $this->bladeCompile($campaign->content, ['subscriber_name' => $campaign->subaccount->user->name, 'subscriber_email' => $sender_email]);
                $subject  = $this->bladeCompile($campaign->subject_a, ['subscriber_name' => $campaign->subaccount->user->name, 'subscriber_email' => $sender_email]);
            }
            catch(Exception $e)
            {
                return $e->getMessage();
            }

            $mail_content = $content.'<div style="text-align: center;"><br/><br/><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->address.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->city.' '.$campaign->subaccount->user->profile->state.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->country->name.'</p><br/></div>';

            // send the message
            $result = $this->sendgridSend($mailApi->api_key, $sender_email, $sender_email, $subject,$mail_content);
        }
    }

    private function sendNotification(Campaign $campaign, $sender_email)
    {
        $data = [
            'campaign_id' => $campaign->id,
            'campaign_name' => $campaign->title,
            'created_at' => (new DateTime($campaign->created_at))->format('M j, Y H:i:s'),
            'sent_at' => (new DateTime($campaign->send_date))->format('M j, Y H:i:s'),
            'num_of_contacts' => $campaign->contacts->count(),
            'sender_email' => $sender_email
        ];
        
        \Mail::send('emails.campaignsent', $data, function($mail) use($data){
            $mail->to($data['sender_email']);
            $mail->from('support@pixlypro.com');
            $mail->subject('[Sendmunk] Your Campaign Has Been Sent');
        });
        return;
    }

    private function replaceImagesWithLinks(Campaign $campaign)
    {
        $content = $campaign->content;

        $dom = new \DomDocument();
		$dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
		$images = $dom->getElementsByTagName('img');
			
		// foreach <img> in the submited message
		foreach($images as $img){
            $src = $img->getAttribute('src');
			
			// if the img source is 'data-url'
			if(preg_match('/data:image/', $src)){
				// get the mimetype
				preg_match('/data:image\/(?<mime>.*?)\;/', $src, $groups);
				$mimetype = $groups['mime'];
				
				// Generating a random filename
				$filename = uniqid().time();
                $filePath = public_path().'/content_images/';
                if (!\File::isDirectory($filePath))
                { 
                    \File::makeDirectory($filePath);
                }
	
				$image = Image::make($src)
				  ->encode($mimetype, 100)
				  ->save($filePath.$filename.'.'.$mimetype);
				
				$new_src = asset('content_images/'.$filename.'.'.$mimetype);
				$img->removeAttribute('src');
				$img->setAttribute('src', $new_src);
			}
		}
		
		$campaign->content = $dom->saveHTML();
		$campaign->save();

    }
}