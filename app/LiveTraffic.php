<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LiveTraffic extends Model
{
    use SoftDeletes;
    
    protected $table = 'live_traffic';

    const TEXT_SEARCHABLE = [
        'ip',
        'user_agent',
        'browser',
        'browser_code',
        'os',
        'os_code',
        'device_type',
        'country',
        'country_code',
        'request_uri',
        'referer'
    ];

    const SPECIFIC_SEARCHABLE = [
        'id',
        'bot',
        'date',
        'time',
        'uniquev',
        'website_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    const CAN_ADMIN_SEARCH = true;
    const CAN_USER_SEARCH = true;
    const CAN_PUBLIC_SEARCH = false;

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
        'website_id',
    ];
}
