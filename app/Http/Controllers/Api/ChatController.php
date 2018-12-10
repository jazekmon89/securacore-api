<?php

namespace App\Http\Controllers\Api;

use App\ChatMessage;
use App\ChatSession;
use App\ChatSessionUser;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    
    public function userRetrieveMessagesBySession() {

    }

	public function store(ChatSession $session, ChatStoreRequest $request) {
		$to_return = [
			'success' => 0,
			'message' => 'User authentication required!'
		];
		$http_code = 401;
		$user = auth()->user();
		if (ChatSessionUser::where('user_id', $user->id)->exists() && ApiHelper::canAuthenticatedAccess()) {
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
	}

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
