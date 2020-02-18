<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RssFeed extends Model
{
    //
    public function subaccount()
    {
        return $this->belongsTo('App\SubAccount', 'sub_account_id');
    }

    public function contacts()
    {
        return $this->belongsToMany('App\Contact')
        ->withTimestamps();
    }

    public function feedContents()
    {
        return $this->hasMany('App\FeedContent', 'rss_feed_id');
    }
}
