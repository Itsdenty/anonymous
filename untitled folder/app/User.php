<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasPushSubscriptions, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function subAccounts()
    {
        return $this->hasMany('App\SubAccount', 'user_id');
    }

    public function currentAccount()
    {
        return $this->hasOne('App\SubAccount', 'id', 'current_sub_account_id');
    }

    /**
     * Get members of a user
     */
    public function members()
    {
        return $this->hasMany('App\Member', 'leader_user_id');
    }

    public function userLeader()
    {
        return $this->hasOne('App\User', 'id', 'leader_id');
    }

    public function profile()
    {
        return $this->hasOne('App\Profile',  'user_id');
    }

    public function member()
    {
        return $this->hasOne('App\Member', 'user_id');
    }

    public function pushSubscriptions()
    {
        return $this->hasMany('App\PushSubscription', 'user_id');
    }
}
