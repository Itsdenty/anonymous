<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    protected $guarded = [];

    public function contacts(){
        return $this->belongsToMany(Contact::class)->withPivot('sub_account_id');
    }
}
