<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdBlockSecurity extends Model
{
    use SoftDeletes;
    
    protected $table = 'adblocker_settings';
    const ACTIVATOR_FIELD = 'detection';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'detection',
        'website_id',
    ];
}
