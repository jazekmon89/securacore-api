<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SQLSecurity extends Model
{
    protected $table = 'sqli_settings';
    const ACTIVATOR_FIELD = 'sql_injection';
}
