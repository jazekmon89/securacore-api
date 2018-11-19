<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProxySecurity extends Model
{
    protected $table = 'proxy_settings';
    const ACTIVATOR_FIELD = 'proxy';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'proxy',
        'proxy_headers',
        'ports',
        'logging',
        'autoban',
        'redirect',
        'mail'
    ];
}
