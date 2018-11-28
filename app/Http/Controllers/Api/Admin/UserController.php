<?php

namespace App\Http\Controllers\Api\Admin;

use App\User;
use App\Helpers\ApiHelper;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\UserStoreRequest;
use App\Http\Requests\Api\Admin\UserUpdateRequest;
use App\Http\Requests\Api\IndexFilterRequest;
use App\Http\Requests\Api\Admin\ChangePasswordRequest;
use App\Notifications\AdminUserRegistrationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

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
                    //$user->{$field} = ($field == 'password' ? bcrypt($value) : $value);
                    $user->{$field} = $value;
                }
            }
            if (!empty($request)) {
                $user->status = 1;
                $user->role = 2;
                $password = Helper::generatePassword();
                $user->password = bcrypt($password);
                $user->save();
                $user->password = $password;
                Notification::send($user, new AdminUserRegistrationNotification($password));
                $to_return = $user->toArray();
            }
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
        if (ApiHelper::isAdmin()) {
            $user->delete();
            $to_return = ['success'=>true];
        }
        return response()->json($to_return, 200);
    }

    public function changePassword(User $user, ChangePasswordRequest $request) {
        $to_return = [
            'success' => 0,
            'error' => "Failed to change password."
        ];
        $http_code = 400;
        if (ApiHelper::isAdmin()) {
            $user->password = $request->get('password');
            $user->save();
            $to_return = [
                'success' => 1
            ];
            $http_code = 200;
            $website = Website::where('user_id', $user->id)->first();
            Notification::send($user, new AdminUserChangePassword($website));
        }
        return response()->json($to_return, $http_code);
    }
    
}
