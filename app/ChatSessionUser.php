<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatSessionUser extends Model
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
    
    protected $table = 'chat_session_users';
}
