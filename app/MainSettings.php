<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainSettings extends Model
{
    protected $table = 'settings';

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
