<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentSecurity extends Model
{
    use SoftDeletes;
    
    protected $table = 'content_security';
    const ACTIVATOR_FIELD = 'enabled';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'function',
        'enabled',
        'website_id',
    ];
}
