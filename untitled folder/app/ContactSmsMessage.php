<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactSmsMessage extends Model
{
    //
    protected $fillable = ['unread'];

    public function contact()
    {
        return $this->belongsTo('App\Contact', 'contact_id');
    }
}
