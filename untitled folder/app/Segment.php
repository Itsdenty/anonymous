<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    protected $table = 'segments';

    public function contacts() {
        return $this->belongsToMany(Contact::class)->withPivot('sub_account_id');
    }
}

