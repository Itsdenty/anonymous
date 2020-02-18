<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Laravel\Passport\HasApiTokens;
use App\UnsubscribedReason;

class SubAccount extends Model
{
    use Notifiable, HasPushSubscriptions, HasApiTokens;

    /**
     * Get the user that owns the sub account.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function forms()
    {
        return $this->hasMany('App\Form', 'sub_account_id');
    }

    public function fromReplyEmails()
    {
        return $this->hasMany('App\FromReplyEmail', 'sub_account_id');
    }

    public function smtps()
    {
        return $this->hasMany('App\Smtp', 'sub_account_id');
    }

    public function mailApis()
    {
        return $this->hasMany('App\MailApi', 'sub_account_id');
    }

    public function campaigns()
    {
        return $this->hasMany('App\Campaign', 'sub_account_id');
    }

    public function contacts()
    {
        return $this->hasMany('App\Contact', 'sub_account_id');
    }

    public function tags()
    {
        return $this->hasMany('App\Tag', 'sub_account_id');
    }

    public function sequences()
    {
        return $this->hasMany('App\Sequence', 'sub_account_id');
    }

    public function segments()
    {
        return $this->hasMany('App\Segment', 'sub_account_id');
    }

    public function contactAttributes()
    {
        return $this->hasMany('App\ContactAttribute', 'sub_account_id');
    }

    public function rssFeeds()
    {
        return $this->hasMany('App\RssFeed', 'sub_account_id');
    }

    public function templates()
    {
        return $this->hasMany('App\Template', 'sub_account_id');
    }

    public function pushSubscriptions()
    {
        return $this->hasMany('App\PushSubscription', 'sub_account_id');
    }

    public function smsMmsIntegrations()
    {
        return $this->hasMany('App\SmsMmsIntegration', 'sub_account_id');
    }

    public function smsCampaigns()
    {
        return $this->hasMany('App\SmsCampaign', 'sub_account_id');
    }

    public function unsubscribeReasons()
    {
        return $this->hasMany('App\UnsubscribeReason', 'sub_account_id');
    }

    protected static function boot() {
        parent::boot();
    

        static::created(function($subaccount) {
            $default_reasons = ["I receive Emails too often", "The Emails are spam", "The Emails are Inappropriate", "I no longer what to receive this emails", "I never signed up for this mailing list"];

            foreach($default_reasons as $reason)
            {
                $unsubscribe_reason = new UnsubscribeReason();
                $unsubscribe_reason->title = $reason;
                $unsubscribe_reason->sub_account_id = $subaccount->id;
                $unsubscribe_reason->save();
            }
        });
    }
}
