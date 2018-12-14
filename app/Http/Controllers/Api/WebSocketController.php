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
        echo "\033[35m Chat server is up! \033[0m \n";
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
        //$this->checkChatSessions();
    }
    /**
     * [onMessage description]
     * @method onMessage
     * @param  ConnectionInterface $conn [description]
     * @param  [JSON.stringify]          $msg  [description]
     * @return [JSON]                    [description]
     * @example message                  conn.send(JSON.stringify({command: "message", to: "1", from: "9", message: "it needs xss protection"}));
     * @example register                 conn.send(JSON.stringify({command: "register", userId: 9}));
     */
    public function onMessage(ConnectionInterface $conn, $msg)
    {
        // TODO: implement queueing!
        $data = json_decode($msg);
        $key = null;
        $has_key = false;
        if (isset($data->public_key)  || isset($data->chat_token)) {
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
            echo "\033[35m Chat token found in request. An admin is registering! \033[0m \n";
            $user = User::where('admin_chat_token', $data->chat_token)->first();
            if ($user) {
                
            }
        }
        if ($has_key) {
            echo "\033[32m Public key or chat token found in request! \033[0m \n";
        } else {
            echo "\033[33m Public key or chat token not found in request! \033[0m \n";
        }
        if ($user) {
            echo "\033[32m User exists with the given public key or chat token! \033[0m \n";
        } else {
            echo "\033[33m User not found with the given public key or chat token! \033[0m \n";
        }
        if (!$has_key || !$user) {
            $conn->send(json_encode([
                'success' => 0,
                'message' => 'Unauthorized access!'
            ]));
        }else if (isset($data->command)) {
            switch ($data->command) {
                case "message":
                    if (isset($data->session_id)) {
                        $message_data = $this->chat->processMessage($user->id, $data->session_id, $data->message);
                        $datetime = $message_data['chat_message_datetime'];
                        $resources = $message_data['resources'];
                        if ($resources && count($resources)) {
                            foreach ($resources as $resource) {
                                if ($resource['resource_id'] && intval($conn->resourceId) != intval($resource['resource_id'])) {
                                    $user = User::where('id', $resource['user_id']);
                                    if ($user->exists()) {
                                        $user = $user->first();
                                        $data->user_info = [
                                            'id' => $user->id,
                                            'fullname' => $user->first_name . ' ' . $user->last_name
                                        ];
                                        $data->datetime = $datetime;
                                        $msg = json_encode($data);
                                    }
                                    if (isset($this->users[$resource['resource_id']])) {
                                        $this->users[$resource['resource_id']]->send($msg);
                                    }
                                }
                            }
                            $conn->send(json_encode([
                                'success' => 0,
                                'message' => 'Message was successfully sent.'
                            ]));
                        } else if ($resources === false) {
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
                case "chat_history":
                    $history = $this->chat->chat_history($user->id, $user->role);
                    $conn->send(json_encode($history));
                break;
                case "register":
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
                    $conn->send(json_encode($to_send));
                break;
                case "reconnect":

                break;
                case "end_chat":
                    if (isset($data->session_id)) {
                        $resource_ids = $this->chat->end($data->session_id);
                        $end_msg = [
                            'success' => 1,
                            'message' => 'Chat has been ended.'
                        ];
                        // send to all users in the channel except me
                        foreach ($resource_ids as $resource_id) {
                            if (isset($this->users[$resource_id]) && $conn->resourceId != $resource_id) {
                                $this->users[$resource_id]->send($end_msg);
                            }
                        }
                        // send to myself
                        $conn->send(json_encode($end_msg));
                    } else {
                        $conn->send(json_encode([
                            'success' => 0,
                            'message' => 'Failed to end chat: session id not provided'
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
        echo "\033[37m Connection {$conn->resourceId} has disconnected \033[0m \n";
        unset($this->users[$conn->resourceId]);
    }
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "\033[31m An error has occurred: \033[0m \n";
        dump($e);
        $conn->close();
    }
    private function checkChatSessions()
    {
        $this->chat->cleanUpChatSessions($this->users);
    }
}