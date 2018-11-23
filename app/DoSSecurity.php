<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoSSecurity extends Model
{
    use SoftDeletes;
    
    protected $table = 'massrequests_settings';
    const ACTIVATOR_FIELD = 'security';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'security',
        'website_id',
    ];
}
