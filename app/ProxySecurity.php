<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProxySecurity extends Model
{
    protected $table = 'proxy_settings';
    const ACTIVATOR_FIELD = 'proxy';
}
