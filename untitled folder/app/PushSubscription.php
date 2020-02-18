<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    //
    protected $fillable = ['endpoint', 'public_key', 'auth_token', 'push_count', 'sub_account_id'];
}
