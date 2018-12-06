<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BannedCountry extends Model
{
    use SoftDeletes;
    
    protected $table = 'banned_country';

    const TEXT_SEARCHABLE = [
        'name',
        'page_url',
    ];

    const SPECIFIC_SEARCHABLE = [
        'id',
        'whitelist',
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
        'name',
        'page_url',
        'whitelist',
        'website_id',
    ];
}
