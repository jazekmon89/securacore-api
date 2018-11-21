<?php

namespace App\Http\Controllers\Api\Admin;

use App\Client;
use App\User;
use App\Helpers\ApiHelper;
use App\Http\Request\Api\UserRequest;
use Illuminate\Http\Request;


class ClientController extends Controller
{

    public function index() {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $client = Client::get();
            $to_return = $client->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function indexByUserId(User $user) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $client = Client::where('user_id', $user->id)->get();
            $to_return = $client->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function store(ClientRequest $request) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            if (!$request->has('user_id')) {
                return response()->json([
                    'error'=>'user_id needed'
                ], 400);
            }
            $client = new Client();
            $request = $request->all();
            foreach($request as $k=>$i) {
                $client->{$k} = $i;
            }
            $client->save();
            $to_return = $client->getAttributes();
        }
        return response()->json($to_return, 200);
    }

    public function storeWithUserId(User $user, ClientRequest $request) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $client = new Client();
            $request = $request->all();
            foreach($request as $k=>$i) {
                $client->{$k} = $i;
            }
            if (!$request->has('user_id')) {
                $client->user_id = $user->id;
            }
            $client->save();
            $to_return = $client->getAttributes();
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

    public function destroy(Client $client) {
        $client->delete();
        return response()->json(['success'=>true], 200);
    }
    
}
