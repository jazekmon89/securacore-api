<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdBlockSecurity extends Model
{
    protected $table = 'securacore_adblocker_settings';
    const ACTIVATOR_FIELD = 'detection';
}
