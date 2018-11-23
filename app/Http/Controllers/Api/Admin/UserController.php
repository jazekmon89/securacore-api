<?php

namespace App\Http\Controllers\Api\Admin;

use App\User;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Request\Api\Admin\UserRequest;
use Illuminate\Http\Request;


class UserController extends Controller
{

    public function index() {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $user = User::get();
            $to_return = $user->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function store(UserRequest $request) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $user = new User();
            $fillables = $user->getFillable();
            $request = $request->all();
            foreach($request as $field=>$value) {
                if ( ($value || $value === 0) && in_array($field, $fillables) ) {
                    $user->{$k} = $i;
                }
            }
            $user->save();
            $to_return = $user->getAttributes();
        }
        return response()->json($to_return, 200);
    }

    public function show(User $user) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $to_return = $user->getAttributes();
        }
        return response()->json($to_return, 200);
    }

    public function update(User $user, UserRequest $request) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $request = $request->all();
            $fillables = $user->getFillable();
            foreach($request as $field=>$value) {
                if ( ($value || $value === 0) && in_array($field, $fillables) ) {
                    $user->{$k} = $i;
                }
            }
            $user->save();
            $to_return = $user->getAttributes();
        }
        return response()->json($to_return, 200);
    }

    public function destroy(User $user) {
        $user->delete();
        return response()->json(['success'=>true], 200);
    }
    
}
