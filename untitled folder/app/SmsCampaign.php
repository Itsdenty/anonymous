<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsCampaign extends Model
{
    //
    public function subaccount()
    {
        return $this->belongsTo('App\SubAccount', 'sub_account_id');
    }

    public function links()
    {
        return $this->hasMany('App\SmsCampaignLink', 'sms_campaign_id');
    }

    public function contacts()
    {
        return $this->belongsToMany('App\Contact')
        ->withPivot('sent', 'message_id')
        ->withTimestamps();
    }
}
