<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecurityLabel extends Model
{
    use SoftDeletes;

    const TEXT_SEARCHABLE = [
        //
    ];
    const SPECIFIC_SEARCHABLE = [
        //
    ];
    const CAN_ADMIN_SEARCH = true;
    const CAN_USER_SEARCH = false;
    const CAN_PUBLIC_SEARCH = false;
    
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
