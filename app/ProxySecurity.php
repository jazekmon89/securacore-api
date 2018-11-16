<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProxySecurity extends Model
{
    protected $table = 'securacore_proxy_settings';
    const ACTIVATOR_FIELD = 'proxy';
}
