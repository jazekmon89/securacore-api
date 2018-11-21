<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiveTraffic extends Model
{
    protected $table = 'live_traffic';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ip',
        'useragent',
        'browser',
        'os',
        'os_code',
        'device_type',
        'country',
        'country_code',
        'request_uri',
        'referer',
        'bot',
        'date',
        'time',
        'uniquev',
        'client_id',
    ];
}
