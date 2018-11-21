<?php

namespace App\Http\Controllers\Api\Users;

use App\User;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Request\Api\UserRequest;
use Illuminate\Http\Request;


class UserController extends Controller
{

    public function show() {
        $to_return = [];
        if (ApiHelper::canAccess() && auth()->user()) {
            $to_return = auth()->user()->getAttributes();
        }
        return response()->json($to_return, 200);
    }

    public function update(UserRequest $request) {
        $to_return = [];
        if (ApiHelper::canAccess() && auth()->user()) {
            $request = $request->all();
            $user = auth()->user();
            foreach($request as $k=>$i) {
                $user->{$k} = $i;
            }
            $user->save();
            $to_return = $user->getAttributes();
        }
        return response()->json($to_return, 200);
    }
    
}
