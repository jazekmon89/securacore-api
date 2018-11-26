<?php

namespace App\Http\Controllers\Api\Users;

use App\User;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserUpdateRequest;
use Illuminate\Http\Request;


class UserController extends Controller
{

    public function index() {
        $to_return = [];
        $user = auth()->user();
        if (ApiHelper::canAccess() && auth()->user()) {
            $website = User::where('id', $user->id)->get();
            $to_return = $website->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function update(UserUpdateRequest $request) {
        $to_return = [];
        if (ApiHelper::canAccess() && auth()->user()) {
            $request = $request->all();
            $user = auth()->user();
            $fillables = $user->getFillable();
            foreach($request as $field=>$value) {
                if ( ($value || $value === 0) && in_array($field, $fillables) ) {
                    $user->{$field} = $value;
                }
            }
            $user->save();
            $to_return = $user->getAttributes();
        }
        return response()->json($to_return, 200);
    }
    
}
