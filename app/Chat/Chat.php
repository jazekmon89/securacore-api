<?php

namespace App\Chat;

use App\ChatMessage;
use App\ChatSession;
use App\ChatSessionUser;
use App\ChatOnlineAgent;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Ratchet\ConnectionInterface;
use App\Jobs\AgentRoundRobinAssign;

class Chat
{
    
    public function register($user_id, ConnectionInterface $conn) {
    	$user = User::where('id', $user_id)->first();
    	if ($user->role >= 2) {
	    	$session = ChatSession::create([
	    		'initiator_user_id' => $user->id
	    	]);
	    	// create user session
    		ChatSessionUser::create([
    			'chat_session_id' => $session->id,
    			'user_id' => $user->id,
    			'resource_id' => $conn->resourceId
    		]);
    		// create admin/support session (assign admin/support to user)
    		if (!ChatOnlineAgent::whereNotNull('resource_id')->exists()) {
    			return false;
    		} else {
				AgentRoundRobinAssign::dispatch($session->id);
    		}
    		return $session->id;
    	} else {
    		$chat_agent = ChatOnlineAgent::where('user_id', $user_id);
    		if ($chat_agent->exists()) {
    			$chat_agent = $chat_agent->first();
    			$chat_agent->resource_id = $conn->resourceId;
    			$chat_agent->save();
    		} else {
    			ChatOnlineAgent::create([
    				'user_id' => $user_id,
    				'resource_id' => $conn->resourceId
    			]);
    		}
    		$session_users = ChatSessionUser::where('user_id', $user->id)->whereNull('resource_id')->has('session')->get();
    		foreach ($session_users as $session_user) {
    			$session_user->resource_id = $conn->resourceId;
    			$session_user->save();
    		}
    	}
    	return false;
    }

    public function cleanUpChatSessions($users) {
		$resource_ids = [];
        foreach ($users as $user_id => $connection) {
            $resource_ids[] = $connection->resourceId;
        }
        $chat_session_users = ChatSessionUser::whereNotIn('resource_id', $resource_ids)->pluck('chat_session_id')->all();
        ChatSession::whereIn('id', $chat_session_users)->delete();
    }

    public function processMessage($user_id, $session_id, $message) {
    	$session = ChatSession::where('id', $session_id);
    	if ($session->exists()) {
    		if (ChatSessionUser::where('chat_session_id', $session_id)->where('user_id', $user_id)->exists()) {
	    		$session_user = ChatSessionUser::where('chat_session_id', $session_id)->where('user_id', '!=', $user_id);
	    		if ($session_user->exists()) {
	    			ChatMessage::create([
	    				'user_id' => $user_id,
	    				'message' => $message,
	    				'chat_session_id' => $session_id
	    			]);
	    			return $session_user->pluck('resource_id')->all();
	    		}
	    	}
    	}
    	return [];
    }

    public function end($session_id) {
        ChatSessionUser::where('chat_session_id', $session_id)->delete();
        ChatSession::where('id', $session_id)->delete();
    }

    public function clearAgentResourceId($resource_id) {
    	$session_user = ChatSessionUser::where('resource_id', $resource_id);
    	if ($session_user->exists()) {
    		$session_user = $session_user->first();
    		$agent = ChatOnlineAgent::where('user_id', $session_user->user_id);
    		if ($agent->exists()) {
    			$agent = $agent->first();
    			$agent->resource_id = null;
    			$agent->save();
    		}
    	}
    }

	/*private function supportBalancing() {
		$users = User::where('role', 1)->get();
		$counts = [];
		foreach ($users as $user) {
			$counts[$user->id] = ChatSessionUser::where('user_id', $user->id)->count();
		}
		asort($counts);
		$user_ids = array_keys($counts);
		if (count($user_ids) == 0) {
			return null;
		}
		return array_shift($user_ids);
	}*/

}
