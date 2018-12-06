<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BannedIP extends Model
{
    use SoftDeletes;
    
    protected $table = 'banned';

    const TEXT_SEARCHABLE = [
        'ip',
        'reason',
        'url',
    ];

    const SPECIFIC_SEARCHABLE = [
        'id',
        'date',
        'time',
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
        'reason',
        'url',
        'website_id'
    ];
}
