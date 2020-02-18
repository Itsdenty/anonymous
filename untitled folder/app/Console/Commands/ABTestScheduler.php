<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Campaign;
use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use Swift_Plugins_AntiFloodPlugin;
use App\MailApi;
use App\Smtp;
use App\FromReplyEmail;
use App\Link;
use App\Traits\SendMailTrait;

class ABTestScheduler extends Command
{
    use SendMailTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'abtest:subject';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send remaining mail depending on the winning subject of the AB Test';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $now = date("Y-m-d H:i", strtotime(Carbon::now())).":00";

        Campaign::where('status', 'progress')->where('actual_send_date', $now)->each(function($campaign){
            $recepients = $campaign->contacts()->where('unsubscribed', false)->wherePivot('sent', false)->get();

            $subject_a_count = $campaign->contacts()->wherePivot('campaign_subject', $campaign->subject_a)->wherePivot('opened', true)->count();
            $subject_b_count = $campaign->contacts()->wherePivot('campaign_subject', $campaign->subject_b)->wherePivot('opened', true)->count();
            
            if($subject_a_count >= $subject_b_count)
            {
                $mail_subject = $campaign->subject_a;
            }
            else
            {
                $mail_subject = $campaign->subject_b;
            }

            // Sender Email
            $sender_email = FromReplyEmail::find($campaign->from_reply_id)->email;
                
            if($campaign->smtp_id != null)
            {
                $smtp = Smtp::find($campaign->smtp_id);
                
                if($smtp)
                {   
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

                    foreach($recepients as $receiver)
                    {
                        try
                        {
                            $content  = $this->bladeCompile($this->replaceCampaignLink($campaign, $receiver->id), ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                            $subject  = $this->bladeCompile($mail_subject, ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                        }
                        catch(Exception $e)
                        {
                            return $e->getMessage();
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
                        try
                        {
                            $result = $mailer->send($message);
                        }
                        catch(\Swift_TransportException $e)
                        {
                            $campaign->contacts()->updateExistingPivot($receiver->id, ['bounced' => true]);
                        }
                        catch(Exception $e)
                        {
                            $campaign->contacts()->updateExistingPivot($receiver->id, ['bounced' => true]);
                        }

                        $campaign->contacts()->updateExistingPivot($receiver->id, ['sent' => true, 'campaign_subject' => $mail_subject, 'message_id' => $message_id]);
                    }
                    
                }
            }
            else if($campaign->mail_api_id != null)
            {
                $mailApi = MailApi::find($campaign->mail_api_id);
                if($mailApi && $mailApi->api_channel_id == 2) //mailgun
                {
                    foreach($recepients as $receiver)
                    {
                        try
                        {
                            $content  = $this->bladeCompile($this->replaceCampaignLink($campaign, $receiver->id), ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                            $subject  = $this->bladeCompile($mail_subject, ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                        }
                        catch(Exception $e)
                        {
                            return $e->getMessage();
                        }
    
                        $mail_content = $content.'<img src="' . url('trackopened'). '/' . $campaign->id . '/'. $receiver->id .'" alt="" width="1" height="1" border="0" />'.'<div style="text-align: center;"><br/><br/><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->address.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->city.' '.$campaign->subaccount->user->profile->state.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->country->name.'</p><br/><a href="'. url('unsubscribe'). '/' . $campaign->id . '/'. $receiver->id .'">Unsubscribe</a> | <a href="'. url('changesubscriber'). '/' . $campaign->id . '/'. $receiver->id .'">Manage my subscription</a></div>';
    
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
                            $campaign->contacts()->updateExistingPivot($receiver->id, ['bounced' => true]);
                        }
    
                        $campaign->contacts()->updateExistingPivot($receiver->id, ['sent' => true, 'campaign_subject' => $mail_subject, 'message_id' => $message_id]);
                    }
                }
                else if($mailApi && $mailApi->api_channel_id == 1) //sendgrid
                {
                    foreach($recepients as $receiver)
                    {
                        try
                        {
                            $content  = $this->bladeCompile($this->replaceCampaignLink($campaign, $receiver->id), ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                            $subject  = $this->bladeCompile($mail_subject, ['subscriber_name' => $receiver->name, 'subscriber_email' => $receiver->email]);
                        }
                        catch(Exception $e)
                        {
                            return $e->getMessage();
                        }
    
                        $mail_content = $content.'<img src="' . url('trackopened'). '/' . $campaign->id . '/'. $receiver->id .'" alt="" width="1" height="1" border="0" />'.'<div style="text-align: center;"><br/><br/><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->address.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->city.' '.$campaign->subaccount->user->profile->state.'</p><p style="margin: 0px; padding: 0px">'.$campaign->subaccount->user->profile->country->name.'</p><br/><a href="'. url('unsubscribe'). '/' . $campaign->id . '/'. $receiver->id .'">Unsubscribe</a> | <a href="'. url('changesubscriber'). '/' . $campaign->id . '/'. $receiver->id .'">Manage my subscription</a></div>';
    
                        // send the message
                        $result = $this->sendgridSend($mailApi->api_key, $sender_email, $receiver->email, $subject,$mail_content);
                        if(!$result)
                        {
                            $campaign->contacts()->updateExistingPivot($receiver->id, ['bounced' => true]);
                        }

                        $campaign->contacts()->updateExistingPivot($receiver->id, ['sent' => true, 'campaign_subject' => $mail_subject, 'message_id' => $result]);
                    }
                }
            }
            $campaign->status = 'sent';
            $campaign->save();
        });
    }
}
