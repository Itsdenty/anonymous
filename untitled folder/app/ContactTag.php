<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactTag extends Model
{
    protected $fillable = ['contact_id', 'tag_id', 'created_at', 'updated_at', 'sub_account_id', 'created_at', 'updated_at'];
    
    protected $table = 'contact_tag';
}
