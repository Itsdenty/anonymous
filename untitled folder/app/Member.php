<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    //
    protected $fillable = [
        'email', 'token',
    ];

    /**
     * Get the user that owns the member.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
