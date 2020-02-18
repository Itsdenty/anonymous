<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Contact;
use App\Sequence;
use App\SequenceEmail;
use App\MailApi;
use App\Traits\SendSequenceMailTrait;

class CheckForActiveSequence extends Command
{
    use SendSequenceMailTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'active:sequence';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for active sequences every minute';

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
        Sequence::where('status', 'active')->each(function($sequence){
            // check sequence filter query
            $collection = Contact::where('sub_account_id', $sequence->sub_account_id)->where('unsubscribed', false)->advancedFilter()->toArray();
            $data = $collection['data'];
            foreach($data as $ct) {
                $sequence->contacts()->syncWithoutDetaching([$ct['id']]);
            }

            $sequence_emails = $sequence->sequenceEmails->where('status', 'active')->sortBy('sort_id');

            $current_send_time = 0;
            foreach($sequence_emails as $email)
            {         
                $this->replaceImagesWithLinks($email);

                foreach($sequence->contacts->where('unsubscribed', false) as $contact) {
                    $sequence->contacts()->updateExistingPivot($contact->id, ['started' => true]);
                    $email->contacts()->syncWithoutDetaching([$contact->id]);
                }

                if($email->time_in_seconds + $current_send_time == 0)
                {
                    //send now
                    $this->storeEmailLinks($email);
                    
                    $contacts = $email->contacts()->wherePivot('unsubscribed', false)->wherePivot('started', false)->wherePivot('sent', false)->get();
                    foreach($contacts as $contact) 
                    {
                        $email->contacts()->updateExistingPivot($contact->id, ['started' => true]);
                        if($sequence->smtp_id != null)
                        {
                            $this->smtpSendMail($email, $contact->id);
                        }
                        else if($sequence->mail_api_id != null)
                        {
                            $mailApi = MailApi::find($sequence->mail_api_id);
                            if($mailApi && $mailApi->api_channel_id == 2)
                            {
                                $this->mailgunSendMail($email, $contact->id);
                            }
                            else if($mailApi && $mailApi->api_channel_id == 1)
                            {
                                $this->sendgridSendMail($email, $contact->id);
                            }
                        }
                        $send_date = date('Y-m-d H:i', strtotime(Carbon::now()));
                        $email->contacts()->updateExistingPivot($contact->id, ['sent' => true, 'send_time' => $send_date]);
                    }

                }
                else
                {         
                    $current_send_time += $email->time_in_seconds;
                    $send_date = date('Y-m-d H:i', (strtotime(Carbon::now()) + $current_send_time));

                    $contacts = $email->contacts()->where('unsubscribed', false)->wherePivot('started', false)->wherePivot('sent', false)->get();
                    foreach($contacts as $contact) {
                        $email->contacts()->updateExistingPivot($contact->id, ['started' => true]);
                        $email->contacts()->updateExistingPivot($contact->id, ['send_time' => $send_date]);
                    }

                }
            }
        });
    }
    
}
