<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BannedCountry extends Model
{
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
        'client_id',
    ];
}
