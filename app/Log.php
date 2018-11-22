<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ip',
        'date',
        'time',
        'page',
        'query',
        'type',
        'browser_name',
        'browser_code',
        'os_name',
        'os_code',
        'country',
        'country_code',
        'region',
        'city',
        'latitude',
        'longitude',
        'isp',
        'user_agent',
        'referer_url',
        'website_id',
    ];
}
