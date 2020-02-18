<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sequence extends Model
{
    //
    public function subaccount()
    {
        return $this->belongsTo('App\SubAccount', 'sub_account_id');
    }

    public function sequenceEmails()
    {
        return $this->hasMany('App\SequenceEmail', 'sequence_id');
    }

    public function contacts()
    {
        return $this->belongsToMany('App\Contact')
        ->withPivot('started')
        ->withTimestamps();
    }
}
