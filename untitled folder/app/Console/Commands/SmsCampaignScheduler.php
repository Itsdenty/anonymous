<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\SmsCampaign;
use App\Traits\SendSmsTrait;

class SmsCampaignScheduler extends Command
{
    use SendSmsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smscampaign:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send sms at scheduled time';

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
        $now = date("Y-m-d H:i", strtotime(Carbon::now())).":00";

        SmsCampaign::where('status', 'later')->where('actual_send_date', $now)->each(function($campaign){
            $recepients = $campaign->contacts->where('unsubscribed', false);

            if($campaign->sms_mms_integration_id != null)
            {
                $this->twilioSendSms($campaign);
            }
            $campaign->status = 'sent';
            $campaign->save();
        });
    }
}
