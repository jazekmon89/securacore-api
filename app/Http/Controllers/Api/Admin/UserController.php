<?php

namespace App\Http\Controllers\Api\Admin;

use App\User;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\UserStoreRequest;
use App\Http\Requests\Api\Admin\UserUpdateRequest;
use App\Http\Requests\Api\IndexFilterRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(IndexFilterRequest $request) {
        $to_return = [];
        if (ApiHelper::isAdmin()) {
            $per_page = $request->get('per_page') ?? 10;
            $page = $request->get('page') ?? 1;
            $to_return = User::paginate($per_page, array('*'), 'page', $page)
                ->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function store(UserStoreRequest $request) {
        $to_return = [];
        if (ApiHelper::isAdmin()) {
            $user = new User();
            $fillables = $user->getFillable();
            $request = $request->all();
            foreach($request as $field=>$value) {
                if ( ($value || $value === 0) && in_array($field, $fillables) ) {
                    $user->{$field} = ($field == 'password' ? bcrypt($value) : $value);
                }
            }
            $user->save();
            $to_return = $user->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function show(User $user) {
        $to_return = [];
        if (ApiHelper::isAdmin()) {
            $to_return = $user->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function update(User $user, UserUpdateRequest $request) {
        $to_return = [];
        if (ApiHelper::isAdmin()) {
            $request = $request->all();
            $fillables = $user->getFillable();
            foreach($request as $field=>$value) {
                if ( ($value || $value === 0) && in_array($field, $fillables) ) {
                    $user->{$field} = $value;
                }
            }
            $user->save();
            $to_return = $user->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function destroy(User $user) {
        $to_return = [];
        if (isAdmin()) {
            $user->delete();
            $to_return = ['success'=>true];
        }
        return response()->json($to_return, 200);
    }
    
}
