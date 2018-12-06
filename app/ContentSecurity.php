<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentSecurity extends Model
{
    use SoftDeletes;
    
    protected $table = 'content_security';
    const ACTIVATOR_FIELD = 'enabled';

    const TEXT_SEARCHABLE = [
        //
    ];

    const SPECIFIC_SEARCHABLE = [
        'id',
        'enabled',
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
        'function',
        'enabled',
        'website_id',
    ];
}
