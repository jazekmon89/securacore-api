<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SQLSecurity extends Model
{
    use SoftDeletes;
    
    protected $table = 'sqli_settings';
    const ACTIVATOR_FIELD = 'sql_injection';

    const TEXT_SEARCHABLE = [
        //
    ];

    const CAN_ADMIN_SEARCH = true;
    const CAN_USER_SEARCH = false;
    const CAN_PUBLIC_SEARCH = false;

    const SPECIFIC_SEARCHABLE = [
        'id',
        'sql_injection',
        'xss',
        'clickjacking',
        'mime_mismatch',
        'https',
        'data_filtering',
        'sanitation',
        'php_version',
        'website_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sql_injection',
        'xss',
        'clickjacking',
        'mime_mismatch',
        'https',
        'data_filtering',
        'sanitation',
        'php_version',
        'website_id',
    ];
}
