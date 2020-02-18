<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactCsv extends Model
{
    protected $fillable = ['csv_filename', 'csv_header', 'csv_data', 'sub_account_id'];
}
