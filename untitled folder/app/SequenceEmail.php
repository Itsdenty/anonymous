<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SequenceEmail extends Model
{
    //
    public function sequence()
    {
        return $this->belongsTo('App\Sequence', 'sequence_id');
    }

    public function contacts()
    {
        return $this->belongsToMany('App\Contact')
        ->withPivot('send_time', 'sent', 'opened', 'unsubscribed', 'open_count', 'device', 'browser', 'domain', 'country_name', 'message_id', 'complained', 'bounced', 'hard_bounced')
        ->withTimestamps();
    }

    public function links()
    {
        return $this->hasMany('App\SequenceEmailLink', 'sequence_email_id');
    }
}
