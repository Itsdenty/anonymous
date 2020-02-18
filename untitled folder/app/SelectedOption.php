<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SelectedOption extends Model
{
    //
    public function form()
    {
        return $this->belongsTo('App\Form');
    }
}
