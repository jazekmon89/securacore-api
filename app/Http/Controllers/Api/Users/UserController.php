<?php

namespace App\Http\Controllers\Api\Users;

use App\User;
use App\Website;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexFilterRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Notifications\UserChangePassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;


class UserController extends Controller
{

    public function index(IndexFilterRequest $request) {
        $to_return = [];
        $user = auth()->user();
        if (ApiHelper::canAccess()) {
            $website = User::where('id', $user->id);
            $per_page = $request->get('per_page') ?? 10;
            $page = $request->get('page') ?? 1;
            $to_return = $website->paginate($per_page, array('*'), 'page', $page)->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function update(UserUpdateRequest $request) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $request = $request->all();
            $user = auth()->user();
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

    public function changePassword(ChangePasswordRequest $request) {
        $to_return = [
            'success' => 0,
            'message' => "Failed to change password."
        ];
        $http_code = 400;
        if (ApiHelper::canAccess()) {
            $user = auth()->user();
            $user->password = bcrypt($request->get('password'));
            $user->save();
            $to_return = [
                'success' => 1
            ];
            $http_code = 200;
            $website = Website::where('user_id', $user->id)->first();
            Notification::send($user, new UserChangePassword($website));
            auth()->refresh();
            auth()->logout();
        }
        return response()->json($to_return, $http_code);
    }
    
}
