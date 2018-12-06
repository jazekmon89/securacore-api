<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Website extends Model
{
    use SoftDeletes;
    
    protected $table = 'websites';

    const TEXT_SEARCHABLE = [
        'url',
        'public_key',
        'notes',
    ];

    const SPECIFIC_SEARCHABLE = [
        'id',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    const CAN_ADMIN_SEARCH = true;
    const CAN_USER_SEARCH = true;
    const CAN_PUBLIC_SEARCH = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'url',
        'public_key',
        'is_activated',
        'notes',
        'online',
        'is_scanned',
    ];

}
