<?php

namespace App\Listeners;

use App\User;
use App\Website;
use App\Log;
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
        // dump('listener: ',  $event);
        $admin = User::where('role', 1)->first();
        $website = Website::where('id', $event->clientlog->website_id)->first();
        $client = User::where('id', $website->user_id)->first();
        // dump('$client: ', $client);
        
        //email to admin
        $admin->sendAdminAttackNotification($event->clientlog);
        //email to client
        $client->sendClientAttackNotification($event->clientlog);
        
    }
}
