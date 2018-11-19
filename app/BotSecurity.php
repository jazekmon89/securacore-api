<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BotSecurity extends Model
{
    protected $table = 'badbot_settings';
    const ACTIVATOR_FIELD = 'badbot';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'badbot',
        'fakebot',
        'useragent_header',
        'logging',
        'autoban',
        'mail'
    ];
}
