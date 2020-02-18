<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushMessage extends Model
{
    //
    protected $fillable = ['delivered', 'send_date'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
