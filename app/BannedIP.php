<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BannedIP extends Model
{
    use SoftDeletes;
    
    protected $table = 'banned';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ip',
        'date',
        'time',
        'reason',
        'url',
        'website_id'
    ];
}
