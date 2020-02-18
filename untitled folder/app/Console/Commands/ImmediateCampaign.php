<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Campaign;
use App\MailApi;
use App\Traits\SendMailTrait;

class ImmediateCampaign extends Command
{
    use SendMailTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:immediately';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Campaign Set for Immediately';

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
        Campaign::where('status', 'sent')->where('send_date', null)->each(function($campaign){
            $recepients = $campaign->contacts->where('unsubscribed', false);

            $campaign->send_date = date('Y-m-d H:i', (strtotime(\Carbon\Carbon::now())  + ($campaign->subaccount->user->profile->timezone->offset * 60 * 60)));
            $campaign->status = 'sent';
            $campaign->save();

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
        });
    }
}
