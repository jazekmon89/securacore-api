<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdBlockSecurity extends Model
{
    protected $table = 'adblocker_settings';
    const ACTIVATOR_FIELD = 'detection';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'detection',
    ];
}
