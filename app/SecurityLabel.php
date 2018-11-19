<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecurityLabel extends Model
{
    protected $table = 'security_labels';
    protected $hidden = [
    	'created_at',
    	'updated_at',
    	'id'
    ];
}
