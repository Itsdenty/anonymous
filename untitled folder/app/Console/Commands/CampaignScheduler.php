<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Campaign;
use App\MailApi;
use App\Traits\SendMailTrait;

class CampaignScheduler extends Command
{
    use SendMailTrait;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mail as scheduled time';

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

        Campaign::where('status', 'later')->where('actual_send_date', $now)->each(function($campaign){
            $recepients = $campaign->contacts->where('unsubscribed', false);

            if($campaign->smtp_id != null)
            {
                $this->smtpSendMail($campaign);
            }
            else if($campaign->mail_api_id != null)
            {
                $mailApi = MailApi::find($campaign->mail_api_id);
                if($mailApi && $mailApi->api_channel_id == 2)
                {
                    $this->mailgunSendMail($campaign);
                }
                else if($mailApi && $mailApi->api_channel_id == 1)
                {
                    $this->sendgridSendMail($campaign);
                }
            }
            $campaign->status = 'sent';
            $campaign->save();
        });
    }
}
