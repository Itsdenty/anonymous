<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $guarded = [];
    //
    public function subaccount()
    {
        return $this->belongsTo('App\SubAccount', 'sub_account_id');
    }

    public function links()
    {
        return $this->hasMany('App\Link', 'campaign_id');
    }

    public function contacts()
    {
        return $this->belongsToMany('App\Contact')
        ->withPivot('campaign_subject', 'sent', 'opened', 'unsubscribed', 'open_count', 'device', 'browser', 'domain', 'country_name', 'message_id', 'complained', 'bounced', 'hard_bounced')
        ->withTimestamps();
    }
}
