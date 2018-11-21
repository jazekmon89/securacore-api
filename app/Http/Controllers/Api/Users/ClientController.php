<?php

namespace App\Http\Controllers\Api\Users;

use App\Client;
use App\User;
use App\Helpers\ApiHelper;
use App\Http\Request\Api\UserRequest;
use Illuminate\Http\Request;


class ClientController extends Controller
{

    public function index() {
        $to_return = [];
        $user = auth()->user();
        if (ApiHelper::canAccess() && auth()->user()) {
            $client = Client::where('user_id', $user->id)->get();
            $to_return = $client->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function show(Client $client) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $to_return $client->getAttributes();
        }
        return response()->json($to_return, 200);
    }

    public function update(Client $client, ClientRequest $request) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $request = $request->all();
            foreach($request as $k=>$i) {
                $client->{$k} = $i;
            }
            $client->save();
            $to_return = $client->getAttributes();
        }
        return response()->json($to_return, 200);
    }
    
}
