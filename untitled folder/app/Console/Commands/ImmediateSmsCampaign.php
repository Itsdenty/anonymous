<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\SmsCampaign;
use App\Traits\SendSmsTrait;

class ImmediateSmsCampaign extends Command
{
    use SendSmsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smscampaign:immediately';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS Campaign set for immediately';

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
        SmsCampaign::where('status', 'sent')->where('send_date', null)->each(function($campaign){
            $recepients = $campaign->contacts->where('unsubscribed', false);

            $campaign->send_date = date('Y-m-d H:i', (strtotime(\Carbon\Carbon::now())  + ($campaign->subaccount->user->profile->timezone->offset * 60 * 60)));
            $campaign->status = 'sent';
            $campaign->save();

            if($campaign->sms_mms_integration_id != null)
            {
                $this->twilioSendSms($campaign);
            }
        });
    }
}
