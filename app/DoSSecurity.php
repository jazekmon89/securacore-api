<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoSSecurity extends Model
{
    protected $table = 'massrequests_settings';
    const ACTIVATOR_FIELD = 'security';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'security',
        'logging',
        'autoban',
        'redirect',
        'mail'
    ];
}
