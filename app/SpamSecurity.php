<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpamSecurity extends Model
{
    use SoftDeletes;
    
    protected $table = 'spam_settings';
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
