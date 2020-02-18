<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class PushNotification extends Notification
{
    use Queueable;

    public $title;
    public $body;
    public $photo;
    public $website;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $body, $photo, $website)
    {
        $this->title = $title;
        $this->body = $body;
        $this->photo = $photo;
        $this->website = $website;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast', WebPushChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Hello from Sendmunk!',
            'body' => 'Thank you for using our application.',
            'photo' => '/logo/compressed_logo.png',
            'action_url' => 'https://sendmunk.com',
            'created' => Carbon::now()->toIso8601String()
        ];
    }

    /**
     * Get the web push representation of the notification.
     *
     * @param  mixed  $notifiable
     * @param  mixed  $notification
     * @return \Illuminate\Notifications\Messages\DatabaseMessage
     */
    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->title)
            ->icon("/pushImages/" . $this->photo)
            ->body($this->body)
            ->action('View app', $this->website)
            ->data(['url' => $this->website]);
    }
}
