<?php

namespace App\Chat;

use App\ChatSession;
use App\Http\Requests\Api\ChatStoreRequest;
use Ratchet\ConnectionInterface;

Interface ChatInterface {

	public function registerClientAndAutoAssignAgent($user_id, ConnectionInterface $conn);
	/*public function userRetrieveMessagesBySession();
	public function store(ChatSession $session, ChatStoreRequest $request)
	public function initiate(ChatStoreRequest $request);
	public function endSession(ChatSession $session);*/

}