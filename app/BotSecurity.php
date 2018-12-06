<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BotSecurity extends Model
{
    use SoftDeletes;
    
    protected $table = 'badbot_settings';
    const ACTIVATOR_FIELD = 'badbot';

    const TEXT_SEARCHABLE = [
        //
    ];

    const SPECIFIC_SEARCHABLE = [
        'id',
        'badbot',
        'fakebot',
        'useragent_header',
        'website_id',
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
        'badbot',
        'fakebot',
        'useragent_header',
        'website_id',
    ];
}
