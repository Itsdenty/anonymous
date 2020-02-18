<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    //
    protected $fillable = [
        'form_type', 'background_color', 'button_text_color', 'button_color', 'background_overlay', 'border_size', 'border_color', 'border_style', 'button_font_size', 'button_font_family'
    ];

    public function forms()
    {
        return $this->hasMany('App\Form', 'theme_id');
    }
}
