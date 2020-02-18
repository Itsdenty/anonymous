<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function timezone()
    {
        return $this->hasOne('App\TimeZones', 'id','time_zone_id');
    }

    public function country()
    {
        return $this->hasOne('App\Country', 'id','country_id');
    }
}
