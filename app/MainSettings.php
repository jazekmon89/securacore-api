<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainSettings extends Model
{
    use SoftDeletes;
    
    protected $table = 'settings';

    const TEXT_SEARCHABLE = [
        //
    ];

    const SPECIFIC_SEARCHABLE = [
        'id',
        'realtime',
        'mail',
        'ip_check',
        'countryban',
        'live_traffic',
        'jquery',
        'error_reporting',
        'display_errors',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    const CAN_ADMIN_SEARCH = true;
    const CAN_USER_SEARCH = false;
    const CAN_PUBLIC_SEARCH = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'realtime',
        'mail',
        'ip_check',
        'countryban',
        'live_traffic',
        'jquery',
        'error_reporting',
        'display_errors',
        'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
