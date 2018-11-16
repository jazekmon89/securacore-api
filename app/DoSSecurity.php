<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoSSecurity extends Model
{
    protected $table = 'securacore_massrequests_settings';
    const ACTIVATOR_FIELD = 'security';
}
