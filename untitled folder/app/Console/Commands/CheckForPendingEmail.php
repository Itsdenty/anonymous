<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\SendSequenceMailTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\SequenceEmail;
use App\MailApi;
use App\Contact;

class CheckForPendingEmail extends Command
{
    use SendSequenceMailTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pending:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks to see if a sequence mail is still pending';

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

        $pending_contact_emails = DB::table('contact_sequence_email')->where('send_time', $now)->where('started', true)->where('sent', false)->get();
        foreach($pending_contact_emails as $contact_email)
        {
            $email = SequenceEmail::find($contact_email->sequence_email_id);
            $contact = Contact::find($contact_email->contact_id);
            if($email && $contact)
            {
                $this->storeEmailLinks($email);
                $sequence = $email->sequence;

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
                $email->contacts()->updateExistingPivot($contact->id, ['sent' => true]);
            }
        }

    }
}
