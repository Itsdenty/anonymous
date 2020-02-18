<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Smtp extends Model
{
    //

    protected $fillable = [
        'title', 'smtp_type_id', 'server', 'port', 'user', 'password', 'api_key', 'smtp_domain', 'domain', 'is_limited', 'sending_limit', 'time_value', 'time_unit', 'encryption'
    ];

    public function subaccount()
    {
        return $this->belongsTo('App\SubAccount');
    }
}
