<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsCampaignLink extends Model
{
    //
    public function smsCampaign()
    {
        return $this->belongsTo('App\SmsCampaign', 'sms_campaign_id');
    }

    public function contacts()
    {
        return $this->belongsToMany('App\Contact')
        ->withPivot('click_count', 'device', 'browser', 'domain', 'country_name')
        ->withTimestamps();
    }
}
