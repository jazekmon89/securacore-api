<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProxySecurity extends Model
{
    use SoftDeletes;
    
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
        'website_id',
    ];
}
