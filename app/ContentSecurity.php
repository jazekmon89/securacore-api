<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContentSecurity extends Model
{
    protected $table = 'content_security';
    const ACTIVATOR_FIELD = 'enabled';
}
