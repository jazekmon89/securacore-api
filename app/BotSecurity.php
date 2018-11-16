<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BotSecurity extends Model
{
    protected $table = 'badbot_settings';
    const ACTIVATOR_FIELD = 'badbot';
}
