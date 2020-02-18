<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailApi extends Model
{
    //
    protected $fillable = [
        'title', 'api_channel_id','domain','api_key'
    ];

    public function subaccount()
    {
        return $this->belongsTo('App\SubAccount');
    }
}
