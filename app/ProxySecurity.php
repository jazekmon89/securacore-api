<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProxySecurity extends Model
{
    use SoftDeletes;
    
    protected $table = 'proxy_settings';
    const ACTIVATOR_FIELD = 'proxy';

    const TEXT_SEARCHABLE = [
        //
    ];

    const SPECIFIC_SEARCHABLE = [
        'id',
        'proxy',
        'proxy_headers',
        'ports',
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
        'proxy',
        'proxy_headers',
        'ports',
        'website_id',
    ];
}
