<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpamSecurity extends Model
{
    protected $table = 'spam_settings';
    const ACTIVATOR_FIELD = 'security';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'security',
    ];
}
