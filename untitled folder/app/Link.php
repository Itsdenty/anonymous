<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    //
    public function campaign()
    {
        return $this->belongsTo('App\Campaign', 'campaign_id');
    }

    public function contacts()
    {
        return $this->belongsToMany('App\Contact')
        ->withPivot('click_count', 'device', 'browser', 'domain', 'country_name')
        ->withTimestamps();
    }
}
