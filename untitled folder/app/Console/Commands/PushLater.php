<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\PushMessage;
use Illuminate\Http\Request;
use App\Notifications\PushNotification;
use App\Jobs\PushNotificationLater;

class PushLater extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:pushlater';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push Scheduled Notification';

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
        $now = date("Y-m-d H:i", strtotime(Carbon::now()));


        PushMessage::where('date_string',  $now)->each(function($message){

            if($message->delivered == 'NO')
            {
                PushNotificationLater::dispatch($message);
                $message->delivered = 'YES';
                $message->save();   
            }
        });
        
    }
}
