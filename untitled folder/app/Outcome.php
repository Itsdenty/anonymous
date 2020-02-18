<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Outcome extends Model
{
    //
    public function form()
    {
        return $this->belongsTo('App\Form');
    }
}
