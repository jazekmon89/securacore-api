<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BannedCountry extends Model
{
    use SoftDeletes;
    
    protected $table = 'banned_country';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'page_url',
        'whitelist',
        'website_id',
    ];
}
