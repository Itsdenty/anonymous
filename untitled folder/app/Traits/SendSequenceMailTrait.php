<?php

namespace App\Traits;

use Illuminate\Support\Carbon;
use App\Campaign;
use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use Swift_Plugins_AntiFloodPlugin;
use SendGrid;
use App\Smtp;
use App\MailApi;
use App\FromReplyEmail;
use App\SequenceEmailLink;
use App\Contact;

trait SendSequenceMailTrait{
    public function smtpSendMail($email, $contact_id)
    {
        $sequence = $email->sequence;

        $smtp = Smtp::find($sequence->smtp_id);
        if($smtp)
        {
            // Sender Email
            $sender_email = FromReplyEmail::find($sequence->from_reply_id)->email;
            
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
            $receiver = Contact::find($contact_id);
            if($receiver)
            {
                try
                {
                    $content  = $this->bladeCompile($this->replaceEmailLink($email, $receiver->id), ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                    $subject  = $this->bladeCompile($email->subject, ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                }
                catch(Exception $e)
                {
                    return back()->with('error', $e->getMessage());
                }

                $message = (new Swift_Message($subject))
                ->setFrom([$sender_email])
                ->setTo([$receiver->email])
                ->setBody($content.'<img src="' . url('tracksequenceemail'). '/' . $email->id . '/'. $receiver->id .'" alt="" width="1" height="1" border="0" />'.'<div style="text-align: center;"><br/><br/><p style="margin: 0px; padding: 0px">'.$sequence->subaccount->user->profile->address.'</p><p style="margin: 0px; padding: 0px">'.$sequence->subaccount->user->profile->city.' '.$sequence->subaccount->user->profile->state.'</p><p style="margin: 0px; padding: 0px">'.$sequence->subaccount->user->profile->country->name.'</p><br/><a href="'. url('unsubscribe_seq/email'). '/' . $email->id . '/'. $receiver->id .'">Unsubscribe</a> | <a href="'. url('changesubscriber_seq'). '/' . $email->id . '/'. $receiver->id .'">Manage my subscription</a></div>','text/html');

                $headers = $message->getHeaders()->get('Message-ID');
                $prefix = 'Message-ID: ';
                $message_id = ''.$headers;
                if (substr($message_id, 0, strlen($prefix)) == $prefix) {
                    $message_id = substr($message_id, strlen($prefix));
                } 

                // Send the message
                try
                {
                    $result = $mailer->send($message);
                }
                catch(\Swift_TransportException $e)
                {
                    $email->contacts()->updateExistingPivot($receiver->id, ['bounced' => true]);
                }
                catch(Exception $e)
                {
                    $email->contacts()->updateExistingPivot($receiver->id, ['bounced' => true]);
                }

                $email->contacts()->updateExistingPivot($receiver->id, ['sent' => true, 'message_id' => $message_id]);
            }
        }
    }

    public function mailgunSendMail($email, $contact_id)
    {   
        $sequence = $email->sequence;
        
        $mailApi = MailApi::find($sequence->mail_api_id);
        if($mailApi && $mailApi->api_channel_id == 2)
        {
            // Sender Email
            $sender_email = FromReplyEmail::find($sequence->from_reply_id)->email;
            
            $receiver = Contact::find($contact_id);
            if($receiver)
            {
                try
                {
                    $content  = $this->bladeCompile($this->replaceEmailLink($email, $receiver->id), ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                    $subject  = $this->bladeCompile($email->subject, ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                }
                catch(Exception $e)
                {
                    return $e->getMessage();
                }

                $mail_content = $content.'<img src="' . url('tracksequenceemail'). '/' . $email->id . '/'. $receiver->id .'" alt="" width="1" height="1" border="0" />'.'<div style="text-align: center;"><br/><br/><p style="margin: 0px; padding: 0px">'.$sequence->subaccount->user->profile->address.'</p><p style="margin: 0px; padding: 0px">'.$sequence->subaccount->user->profile->city.' '.$sequence->subaccount->user->profile->state.'</p><p style="margin: 0px; padding: 0px">'.$sequence->subaccount->user->profile->country->name.'</p><br/><a href="'. url('unsubscribe_seq/email'). '/' . $email->id . '/'. $receiver->id .'">Unsubscribe</a> | <a href="'. url('changesubscriber_seq'). '/' . $email->id . '/'. $receiver->id .'">Manage my subscription</a></div>';

                // send the message
                $result = $this->mailgunSend($mailApi->api_key, $mailApi->domain, $sender_email, $receiver->email, $subject,$mail_content);

                $message_id = null;
                $result_array = json_decode($result, true);
                if(isset($result_array['id']))
                {
                    $message_id = $result_array['id'];
                }
                else
                {
                    $email->contacts()->updateExistingPivot($receiver->id, ['bounced' => true]);
                }

                $email->contacts()->updateExistingPivot($receiver->id, ['sent' => true, 'message_id' => $message_id]);
            }
            
        }
    }

    public function sendgridSendMail($email, $contact_id)
    {
        $sequence = $email->sequence;
        
        $mailApi = MailApi::find($sequence->mail_api_id);
        if($mailApi && $mailApi->api_channel_id == 1)
        {
            // Sender Email
            $sender_email = FromReplyEmail::find($sequence->from_reply_id)->email;


            $receiver = Contact::find($contact_id);
            if($receiver)
            {
                try
                {
                    $content  = $this->bladeCompile($this->replaceEmailLink($email, $receiver->id), ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                    $subject  = $this->bladeCompile($email->subject, ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                }
                catch(Exception $e)
                {
                    return $e->getMessage();
                }

                $mail_content = $content.'<img src="' . url('tracksequenceemail'). '/' . $email->id . '/'. $receiver->id .'" alt="" width="1" height="1" border="0" />'.'<div style="text-align: center;"><br/><br/><p style="margin: 0px; padding: 0px">'.$sequence->subaccount->user->profile->address.'</p><p style="margin: 0px; padding: 0px">'.$sequence->subaccount->user->profile->city.' '.$sequence->subaccount->user->profile->state.'</p><p style="margin: 0px; padding: 0px">'.$sequence->subaccount->user->profile->country->name.'</p><br/><a href="'. url('unsubscribe_seq/email'). '/' . $email->id . '/'. $receiver->id .'">Unsubscribe</a> | <a href="'. url('changesubscriber_seq'). '/' . $email->id . '/'. $receiver->id .'">Manage my subscription</a></div>';

                // send the message
                $result = $this->sendgridSend($mailApi->api_key, $sender_email, $receiver->email, $subject,$mail_content);
                if(!$result)
                {
                    $email->contacts()->updateExistingPivot($receiver->id, ['bounced' => true]);
                }

                $email->contacts()->updateExistingPivot($receiver->id, ['sent' => true, 'message_id' => $result]);
            }
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

    private function sendgridSend($api_key, $sender_mail, $receiver_email, $subject, $msg)
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

    private function storeEmailLinks($email)
    {
        // The Regular Expression filter
        $content = $email->content;
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        $dom = new \DomDocument();
		$dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
		$anchors = $dom->getElementsByTagName('a');

        foreach($anchors as $anchor){
			$link = $anchor->getAttribute('href');
			
            if(preg_match($reg_exUrl, $link))
            {
				if(!SequenceEmailLink::where('sequence_email_id', $email->id)->where('actual_url', $link)->first())
                {
                    $email_link = new SequenceEmailLink();
                    $email_link->sequence_email_id = $email->id;
                    $email_link->actual_url = $link;
                    $email_link->replacement_url = $this->randomLink(10);
                    $email_link->save();
                }
			}
		} 
    }

    private function randomLink($length)
    {    
        do
        {
            $randomCode = str_random($length);
            $link = SequenceEmailLink::where('replacement_url', $randomCode)->first();
        }
        while(!empty($link));        
        return $randomCode; 
    }

    private function replaceEmailLink($email, $contact_id)
    {
        $content = $email->content;

        $links = $email->links;

        foreach($links as $link)
        {
            $content = str_replace($link->actual_url, url('contentlink').'/'.$link->replacement_url.'/'.$contact_id, $content);
        }

        return $content;
    }

    private function replaceImagesWithLinks($email)
    {
        $content = $email->content;

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
		
		$email->content = $dom->saveHTML();
		$email->save();

    }
}