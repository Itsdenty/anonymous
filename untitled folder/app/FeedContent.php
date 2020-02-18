<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedContent extends Model
{
    //
    public function rssfeed()
    {
        return $this->belongsTo('App\RssFeed', 'rss_feed_id');
    }

    public function campaign()
    {
        return $this->hasOne('App\Campaign', 'id', 'campaign_id');
    }
}
