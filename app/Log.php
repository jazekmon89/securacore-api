<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use SoftDeletes;
    
    protected $table = 'logs';

    const TEXT_SEARCHABLE = [
        'page',
        'query',
        'browser_name',
        'browser_code',
        'os_name',
        'os_code',
        'country',
        'country_code',
        'region',
        'city',
        'isp',
        'user_agent',
        'referer_url',
    ];

    const SPECIFIC_SEARCHABLE = [
        'id',
        'date',
        'time',
        'type',
        'latitude',
        'longitude',
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
