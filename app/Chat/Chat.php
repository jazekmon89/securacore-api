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

class Chat implements ChatInterface
{
    
    public function registerClientAndAutoAssignAgent($user_id, ConnectionInterface $conn) {
    	$user = User::where('id', $user_id)->first();
    	if ($user->role >= 2) {
	    	$session = ChatSession::create([
	    		'initiator_user_id' => $user->id
	    	]);
    		$admin_agent = $this->supportBalancing();
    		ChatSessionUser::create([
    			'chat_session_id' => $session->id,
    			'user_id' => $user->id,
    			'resource_id' => $conn->resourceId
    		]);
    		ChatSessionUser::create([
    			'chat_session_id' => $session->id,
    			'user_id' => $admin_agent
    		]);
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

    public function processMessage($session_id) {
    	$session = ChatSession::where('id', $data->session_id);
    	if ($session->exists()) {
    		$session_user = ChatSessionUser::where('chat_session_id', $session->id);
    		if ($session_user->exists()) {
    			return $session_user->pluck('resource_id')->all();
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
    /*public function userRetrieveMessagesBySession(ChatSession $session, $data) {
    	$to_return = [
			'success' => 0,
			'message' => 'User authentication required!'
		];
		$http_code = 401;
		$user = auth()->user();
		if (ChatSessionUser::where('user_id', $user->id)->where('chat_session_id', $session->id)->has('session')->exists() && ApiHelper::canAuthenticatedAccess()) {
			$to_return = ChatMessage::where('user_id', $user->id)->where('chat_session_id', $session->id)->paginate(10, array('*'), 'page', $page)->toArray();
			$htt_code = 200;
		}
		return response()->json($to_return, $http_code);
    }

	public function store(ChatSession $session, ChatStoreRequest $request) {
		$to_return = [
			'success' => 0,
			'message' => 'User authentication required!'
		];
		$http_code = 401;
		$user = auth()->user();
		if (ChatSessionUser::where('user_id', $user->id)->where('chat_session_id', $session->id)->has('session')->exists() && ApiHelper::canAuthenticatedAccess()) {
			$to_return = [];
			$message = new ChatMessage();
			$fillables = $message->getFillable();
			$data = $request->all();
			foreach($data as $field=>$value) {
                if ( ($value || $value === 0) && in_array($field, $fillables) ) {
                	$message->{$field} = $value;
                }
			}
			if (count($request->all())) {
				$message->user_id = $user->id;
				$message->session_id = $session->id;
				$message->save();
				$to_return = [
					'success' => 1
				];
			}
			$http_code = 200;
		}
		return response()->json($to_return, $http_code);
	}

	public function initiate(ChatStoreRequest $request) {
		$to_return = [
			'success' => 0,
			'message' => 'User authentication required!'
		];
		$http_code = 401;
		if (ApiHelper::canAuthenticatedAccess()) {
			$session = ChatSession::create([]);
			$user = auth()->user();
			$session_user = ChatSessionUser::create([
				'session_id' => $session->id,
				'user_id' => $user->id
			]);
			$available_admin = $this->supportBalancing();
			if (!$available_admin) {
				return response()->json([
					'success' => 0,
					'message' => 'No available agents'
				], 204);
			}
			$session_admin = ChatSessionUser::create([
				'session_id' => $session->id,
				'user_id' => $available_admin
			]);
			$to_return = $session;
			$http_code = 200;
		}
		return response()->json($to_return, $http_code);
	}

	public function endSession(ChatSession $session) {
		$session::delete();
		return response()->json([
			'success' => 0
		], 200);
	}*/

	private function supportBalancing() {
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
	}

}
