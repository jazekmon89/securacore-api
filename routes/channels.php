<?php

use Illuminate\Support\Facades\Redis;
use App\User;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('App.User.admin', function ($user, $id) {
    dump('channel $user', $user);
    dump('channel $id', $id);
    // $admin = User::where('role', 1)->first();
    return (int) $user->id === (int) $id;
    return true;
});
