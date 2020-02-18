<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\PushMessage;
use App\Notifications\PushNotification;

class PushNotificationLater implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $push_message;

    public function __construct($push_message)
    {
        $this->push_message = $push_message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->push_message->user->notify(new PushNotification($this->push_message->title, $this->push_message->body, $this->push_message->photo, $this->push_message->website));
    }
}