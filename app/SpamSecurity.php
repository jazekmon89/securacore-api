<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpamSecurity extends Model
{
    protected $table = 'securacore_spam_settings';
    const ACTIVATOR_FIELD = 'security';
}
