<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FromReplyEmail extends Model
{
    public function subaccount()
    {
        return $this->belongsTo('App\SubAccount');
    }
}
