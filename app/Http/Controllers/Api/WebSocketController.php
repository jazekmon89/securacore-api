<?php

namespace App\Http\Controllers\Api;

use App;
use JWTAuth;
use App\Chat\Chat;
use App\Website;
use App\User;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketController implements MessageComponentInterface
{
    protected $clients;
    private $subscriptions;
    private $users;
    private $userresources;
    private $chat;
    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->users = [];
        $this->chat = new Chat();
    }
    /**
     * [onOpen description]
     * @method onOpen
     * @param  ConnectionInterface $conn [description]
     * @return [JSON]                    [description]
     * @example connection               var conn = new WebSocket('ws://localhost:8090');
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $this->users[$conn->resourceId] = $conn;
        $this->checkChatSessions();
    }
    /**
     * [onMessage description]
     * @method onMessage
     * @param  ConnectionInterface $conn [description]
     * @param  [JSON.stringify]              $msg  [description]
     * @return [JSON]                    [description]
     * @example subscribe                conn.send(JSON.stringify({command: "subscribe", channel: "global"}));
     * @example groupchat                conn.send(JSON.stringify({command: "groupchat", message: "hello glob", channel: "global"}));
     * @example message                  conn.send(JSON.stringify({command: "message", to: "1", from: "9", message: "it needs xss protection"}));
     * @example register                 conn.send(JSON.stringify({command: "register", userId: 9}));
     */
    public function onMessage(ConnectionInterface $conn, $msg)
    {

        // TODO: implement queueing!

        echo $msg;
        $data = json_decode($msg);
        $key = null;
        $has_key = false;
        if (isset($data->public_key)) {
            $has_key = true;
        } else if (isset($data->chat_token)) {
            $has_key = true;
        }
        $user = null;
        if (isset($data->public_key)) {
            $website = Website::where('public_key', $data->public_key);
            if ($website->exists()) {
                $website = $website->first();
                $user = User::where('id', $website->user_id)->first();
            }
        } else if(isset($data->chat_token)) {
            $user = User::where('admin_chat_token', $data->chat_token)->first();
        }
        if (isset($data->command)) {
            switch ($data->command) {
                case "message":
                    if (isset($data->session_id) && $has_key && $user) {
                        $resource_ids = $this->chat->processMessage($user->id, $data->session_id, $data->message);
                        if (count($resource_ids)) {
                            foreach ($resource_ids as $resource_id) {
                                if ($resource_id && intval($conn->resourceId) != intval($resource_id)) {
                                    $this->users[$resource_id]->send($msg);
                                }
                            }
                        } else {
                            $conn->send(json_encode([
                                'success' => 0,
                                'message' => 'Failed to send message. You do not belong to this session.'
                            ]));
                        }
                    } else {
                        $conn->send(json_encode([
                            'success' => 0,
                            'message' => 'Failed to send message: missing session_id, token, or access is unauthorized.'
                        ]));
                    }
                break;
                case "register":
                    $to_send = [
                        'success' => 0,
                        'message' => 'Unauthorized access!'
                    ];
                    if ($has_key && $user) {
                        $session_id = $this->chat->register($user->id, $conn);
                        if ($user->role >= 2) {
                            if (!$session_id) {
                                $to_send = [
                                    'success' => 0,
                                    'message' => 'There are no agents available as of the moment.'
                                ];
                            } else {
                                $to_send = [
                                    'success' => 1,
                                    'session_id' => $session_id
                                ];
                            }
                        } else if ($user->role == 1) {
                            $to_send = [
                                'success' => 1
                            ];
                        }
                    }
                    $conn->send(json_encode($to_send));
                break;
                case "end_chat":
                    if (isset($data->session_id)) {
                        $this->chat->end($data->session_id);
                        $conn->send(json_encode([
                            'success' => 1,
                            'message' => 'Chat has been ended.'
                        ]));
                    } else {
                        $conn->send(json_encode([
                            'success' => 0,
                            'message' => 'Failed to end chat.'
                        ]));
                    }
                break;
                default:
                    $example = array(
                        'methods' => [
                            "message" => '{command: "message", session_id: "1", message: "it needs xss protection"}',
                            "register" => '{command: "register"}',
                        ],
                    );
                    $conn->send(json_encode($example));
                break;
            }
        }
    }
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        $this->chat->clearAgentResourceId($resource_id);
        echo "Connection {$conn->resourceId} has disconnected\n";
        unset($this->users[$conn->resourceId]);
    }
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
    private function checkChatSessions()
    {
        $this->chat->cleanUpChatSessions($this->users);
    }
}