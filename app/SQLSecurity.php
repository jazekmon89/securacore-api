<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SQLSecurity extends Model
{
    protected $table = 'sqli_settings';
    const ACTIVATOR_FIELD = 'sql_injection';

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
