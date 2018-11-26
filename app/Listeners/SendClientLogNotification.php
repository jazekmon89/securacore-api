<?php

namespace App\Listeners;

use App\User;
use App\Events\ClientLogSubmitted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendClientLogNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ClientLogSubmitted  $event
     * @return void
     */
    public function handle(ClientLogSubmitted $event)
    {
        dump('listener: ',  $event);
        $admin = User::where('role', 1)->first();
        // $client = User::where('id', $attacked_site->user_id)->first();

        //email to admin
        $admin->sendAttackNotification($all);
        // //email to client
        // $client->sendAttackNotification($clientlog->all());
        
    }
}
