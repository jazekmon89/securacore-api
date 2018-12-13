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
	    		$session_users = ChatSessionUser::where('chat_session_id', $session_id)->where('user_id', '!=', $user_id);
	    		if ($session_users->exists()) {
	    			$admin_users = $session_users->pluck('user_id')->all();
		    		$admin_users = User::whereIn('id', $admin_users)->where('role', 1);
		    		if ($admin_users->exists()) {
		    			$admin_users = $admin_users->pluck('id')->all();
		    			$online_agents = ChatOnlineAgent::whereIn('user_id', $admin_users);
		    			if ($online_agents->exists()) {
		    				$online_agents = $online_agents->get();
		    				foreach ($online_agents as $online_agent) {
		    					$session_admin_user = ChatSessionUser::where('user_id', $online_agent->user_id)
		    						->where('chat_session_id', $session_id);
		    					if ($session_admin_user->exists()) {
		    						$session_admin_user = $session_admin_user->first();
		    						$session_admin_user->resource_id = $online_agent->resource_id;
		    						$session_admin_user->save();
		    					}
		    				}
		    			}
		    		}
	    			ChatMessage::create([
	    				'user_id' => $user_id,
	    				'message' => $message,
	    				'chat_session_id' => $session_id
	    			]);
	    			return $session_users->select('user_id', 'resource_id')->get()->toArray();
	    		}
	    	}
    	}
    	return false;
    }

    public function end($session_id) {
        $session_users = ChatSessionUser::where('chat_session_id', $session_id);
        $current_session_users = $session_users->pluck('resource_id')->all();
        $session_users->delete();
        ChatSession::where('id', $session_id)->delete();
        return $current_session_users;
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

    public function chat_history($user_id) {
        return ChatSession::where('user_id', $user_id)->with('message')->paginate('10', array('*'), 'page', 1)->toArray();
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
