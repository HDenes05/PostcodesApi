<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;
    protected $fillable = ['postal_code', 'place_name', 'county_id'];

}
