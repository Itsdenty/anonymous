<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
