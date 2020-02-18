<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactAttribute extends Model
{
    //
    public function subaccount()
    {
        return $this->belongsTo('App\SubAccount', 'sub_account_id');
    }
}
