<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsMmsIntegration extends Model
{
    //
    protected $fillable = ['current_settings'];


    public function subaccount()
    {
        return $this->belongsTo('App\SubAccount');
    }
}
