<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnsubscribeReason extends Model
{
    //
    public function subaccount()
    {
        return $this->belongsTo('App\SubAccount');
    }
}
