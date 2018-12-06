<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoSSecurity extends Model
{
    use SoftDeletes;
    
    protected $table = 'massrequests_settings';
    const ACTIVATOR_FIELD = 'security';

    const TEXT_SEARCHABLE = [
        //
    ];

    const SPECIFIC_SEARCHABLE = [
        'id',
        'security',
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
        'security',
        'website_id',
    ];
}
