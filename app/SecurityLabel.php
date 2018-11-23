<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecurityLabel extends Model
{
    use SoftDeletes;
    
    protected $table = 'security_labels';
    protected $hidden = [
    	'created_at',
    	'updated_at',
    	'id'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'message',
    ];
}
