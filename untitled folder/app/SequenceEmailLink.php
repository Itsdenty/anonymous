<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SequenceEmailLink extends Model
{
    //
    public function sequenceEmail()
    {
        return $this->belongsTo('App\SequenceEmail', 'sequence_email_id');
    }

    public function contacts()
    {
        return $this->belongsToMany('App\Contact')
        ->withTimestamps();
    }
}
