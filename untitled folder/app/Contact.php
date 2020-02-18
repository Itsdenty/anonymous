<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ContactTrait;



class Contact extends Model
{

    use ContactTrait;

    protected $guarded = [];
    
    public function tags() {
        return $this->belongsToMany(Tag::class)->withPivot('sub_account_id');
    }

    public function segments() {
        return $this->belongsToMany(Segment::class)->withPivot('sub_account_id');
    }

    public function campaigns() {
        return $this->belongsToMany(Campaign::class)->withPivot('contact_id');
    }

    public function smsCampaigns() {
        return $this->belongsToMany(SmsCampaign::class)->withPivot('sub_account_id');
    }

    public function links() {
        return $this->belongsToMany(Link::class)->withPivot('campaign_id');
    }

    public function rssFeed() {
        return $this->belongsToMany(RssFeed::class)->withPivot('sub_account_id');
    }

    public function subaccount()
    {
        return $this->belongsTo('App\SubAccount', 'sub_account_id');
    }

    public function contactAttributes()
    {
        return $this->belongsToMany('App\ContactAttribute')
        ->withPivot('value')
        ->withTimestamps();
    }

    public function contactLinks()
    {
        return $this->belongsToMany('App\Link')
        ->withPivot('click_count', 'device', 'browser', 'domain', 'country_name')
        ->withTimestamps();
    }

    public function contactSmsMessages()
    {
        return $this->hasMany('App\ContactSmsMessage', 'contact_id');
    }

    public function activities()
    {
        return $this->hasMany('App\Activity', 'contact_id');
    }

    protected $allowedFilters = [
        'id', 'name', 'email', 'sub_date', 'tag', 'country_name', 'form_id', 'segment', 'email_openers', 'non_email_openers', 'link_clickers', 'non_link_clickers', 'custom'
    ];

    protected $orderable = [
        'id', 'name', 'email', 'sub_date'
    ];

    protected static function boot() {
        parent::boot();
    
        static::deleting(function($contact) {
            $contact->tags()->detach();
            $contact->segments()->detach();
        });

        static::saving(function($contact) {
            $contact->name = $contact->firstname . ' ' . $contact->lastname;
        });
    }
}
