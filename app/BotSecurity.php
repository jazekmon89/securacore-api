<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BotSecurity extends Model
{
    use SoftDeletes;
    
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
        'website_id',
    ];
}
