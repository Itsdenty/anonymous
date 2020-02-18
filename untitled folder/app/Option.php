<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use File;

class Option extends Model
{
    //
    //
    public function question()
    {
        return $this->belongsTo('App\Question');
    }

    public function outcome()
    {
        return $this->hasOne('App\Outcome', 'outcome_id');
    }

    public static function boot ()
    {
        parent::boot();

        self::deleting(function (Option $option) {

            if($option->image_url)
            {
                $file = public_path().'/'.$option->image_url;
                if(File::isFile($file)){
                    File::delete($file);
                }
            }
        });
    }
}
