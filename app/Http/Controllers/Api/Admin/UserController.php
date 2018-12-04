<?php

namespace App\Http\Controllers\Api\Admin;

use App\Website;
use App\User;
use App\Helpers\ApiHelper;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\UserStoreRequest;
use App\Http\Requests\Api\Admin\UserUpdateRequest;
use App\Http\Requests\Api\IndexFilterRequest;
use App\Http\Requests\Api\Admin\ChangePasswordRequest;
use App\Http\Requests\Api\Admin\UserAndWebsiteStoreRequest;
use App\Jobs\ProcessClientInitialData;
use App\Notifications\AdminUserChangePassword;
use App\Notifications\AdminUserRegistrationNotification;
use App\Notifications\AdminUserAndWebsiteRegistrationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class UserController extends Controller
{

    public function index(IndexFilterRequest $request) {
        $to_return = [];
        $http_code = 401;
        if (ApiHelper::isAdmin()) {
            $per_page = $request->get('per_page') ?? 10;
            $page = $request->get('page') ?? 1;
            $to_return = User::paginate($per_page, array('*'), 'page', $page)
                ->toArray();
            $http_code = 200;
        }
        return response()->json($to_return, $http_code);
    }

    private function storeUser($request, $password) {
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
            $user->password = bcrypt($password);
            $user->save();
        }
        return $user;
    }

    private function storeWebsite($request, $user_id) {
        $website = new Website();
        $request = $request->all();
        $fillables = $website->getFillable();
        foreach($request as $field=>$value) {
            if ( ($value || $value === 0) && in_array($field, $fillables) ) {
                $website->{$field} = $value;
            }
        }
        if (!empty($request)) {
            $website->user_id = $user_id;
            $website->public_key = Helper::generatePublicKey();
            $website->is_activated = 0;
            $website->save();
        }
        return $website;
    }

    public function store(UserStoreRequest $request) {
        $to_return = [];
        $http_code = 401;
        if (ApiHelper::isAdmin()) {
            $password = Helper::generatePassword();
            $user = $this->storeUser($request, $password);
            Notification::send($user, new AdminUserRegistrationNotification($user->password));
            ProcessClientInitialData::dispatch($website);
            $to_return = $user->toArray();
            $http_code = 200;
        }
        return response()->json($to_return, $http_code);
    }

    public function show(User $user) {
        $to_return = [];
        $http_code = 401;
        if (ApiHelper::isAdmin()) {
            $to_return = $user->toArray();
            $http_code = 200;
        }
        return response()->json($to_return, $http_code);
    }

    public function update(User $user, UserUpdateRequest $request) {
        $to_return = [];
        $http_code = 401;
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
            $http_code = 200;
        }
        return response()->json($to_return, $http_code);
    }

    public function destroy(User $user) {
        $to_return = [];
        $http_code = 401;
        if (ApiHelper::isAdmin()) {
            $user->delete();
            $to_return = ['success'=>true];
            $http_code = 200;
        }
        return response()->json($to_return, $http_code);
    }

    public function changePassword(User $user, ChangePasswordRequest $request) {
        $to_return = [
            'success' => 0,
            'message' => "Failed to change password."
        ];
        $http_code = 401;
        if (ApiHelper::isAdmin()) {
            $user->password = bcrypt($request->get('password'));
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

    public function createUserAndWebsite(UserAndWebsiteStoreRequest $request) {
        $to_return = [
            'success' => 0,
            'message' => 'Failed to create User and Website.'
        ];
        $http_code = 401;
        if (ApiHelper::isAdmin()) {
            $password = Helper::generatePassword();
            $user = $this->storeUser($request, $password);
            $website = $this->storeWebsite($request, $user->id);
            Notification::send($user, new AdminUserAndWebsiteRegistrationNotification($website, $password));
            ProcessClientInitialData::dispatch($website);
            $to_return = User::where('id', $user->id)->first();
            $http_code = 200;
        }
        return response()->json($to_return, $http_code);
    }
    
}
