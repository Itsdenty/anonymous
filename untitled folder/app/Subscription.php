<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //
    public function form()
    {
        return $this->belongsTo('App\Form');
    }
}
