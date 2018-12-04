<?php

namespace App\Events;

use App\User;
use App\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Redis;

class ClientLogSubmitted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $clientlog;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Log $clientlog)
    {
        $this->clientlog = $clientlog;
        // dump('events: ', $this->clientlog);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        Redis::publish('client-log', json_encode($this->clientlog));
        $admin = User::where('role', 1)->first();
        // dump('$admin: ', $admin->role);
        return new Channel('admin-client-log');
    }
}
