<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BannedIP extends Model
{
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
