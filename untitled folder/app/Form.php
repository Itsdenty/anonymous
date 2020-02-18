<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use NotificationChannels\WebPush\HasPushSubscriptions;

class Form extends Model
{
    // use Uuids, HasPushSubscriptions;
    use Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'form_type', 'background_color', 'button_text_color', 'button_color', 'position', 'email_label', 'email_placeholder', 'phone_label', 'phone_placeholder', 'button_text', 'trigger_point', 'hide_duration', 'autohide', 'redirect_url', 'desktop_device', 'mobile_text', 'tablet_device', 'theme_id', 'allow_closing', 'background_overlay', 'loading_delay', 'frequency', 'size', 'border_size', 'border_color', 'border_style', 'button_font_size', 'button_font_family', 'option_font_size', 'option_font_family', 'option_color'
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    
    public function subaccount()
    {
        return $this->belongsTo('App\SubAccount', 'sub_account_id');
    }

    public function theme()
    {
        return $this->hasOne('App\Theme', 'id', 'theme_id');
    }

    public function subscriptions()
    {
        return $this->hasMany('App\Subscription', 'form_id');
    }

    // public function clicks()
    // {
    //     return $this->hasMany('App\Click', 'form_id');
    // }

    public function visitors()
    {
        return $this->hasMany('App\Visitor', 'form_id');
    }

    public function displayRules()
    {
        return $this->hasMany('App\DisplayRule', 'form_id');
    }

    public function questions()
    {
        return $this->hasMany('App\Question', 'form_id');
    }

    public function outcomes()
    {
        return $this->hasMany('App\Outcome', 'form_id');
    }

    public function results()
    {
        return $this->hasMany('App\Result', 'form_id');
    }

    public function unverifiedSubscribers()
    {
        return $this->hasMany('App\UnverifiedSubscriber', 'form_id');
    }

    public function selectedOptions()
    {
        return $this->hasMany('App\SelectedOption', 'form_id');
    }

    public function messages(){
        return $this->hasMany('App\PushMessage', 'form_id');
    }

    public function push_subscriptions(){
        return $this->hasMany('App\PushSubscription', 'form_id');
    }
}
