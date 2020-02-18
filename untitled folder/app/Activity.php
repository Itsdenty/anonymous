<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    //
    protected $table = 'activities';

    protected $fillable = ['content', 'category', 'contact_id'];

    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }
}
