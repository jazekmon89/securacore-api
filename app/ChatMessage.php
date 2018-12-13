<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
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
    
    protected $table = 'chat_messages';
    protected $fillable = [
        'user_id',
    	'message',
        'chat_session_id'
    ];
    protected $with = ['user'];

    public function user() {
        $this->belongsTo('App\User', 'id', 'user_id');
    }
}
