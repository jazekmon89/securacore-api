<?php

namespace App\Http\Controllers\Api\Publics;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Publics\PublicKeyRequest;
use App\Http\Requests\Api\Publics\ChangePasswordRequest;
use App\Notifications\PublicChangePassword;
use App\User;
use App\Website;
use Illuminate\Support\Facades\Notification;

class PublicKeyController extends Controller
{

    public function checkAndActivatePublicKey(PublicKeyRequest $request) {
        $field = 'public_key';
        $website = new Website();
        $public_key = $request->get($field) ?? null;
        $http_code = 404;
        $to_return = [
            'success' => 0,
            'message' => "Public key not found, is not yet activated, or your access doesn't match with your info in our system."
        ];
        if (ApiHelper::publicCheckAccess($public_key, $website, $field, $request)) {
            $website = $website->where($field, $public_key)->first();
            if ( !$website->is_activated ) {
                $website->is_activated = 1;
                $website->save();
            }
            $http_code = 200;
            $to_return = [
                'website_id' => $website->id,
                'is_activated' => $website->is_activated
            ];
        }
        return response()->json($to_return, $http_code);
    }

    public function changePassword(ChangePasswordRequest $request) {
        $field = 'public_key';
        $website = new Website();
        $public_key = $request->get($field) ?? null;
        $http_code = 404;
        $to_return = [
            'success' => 0,
            'message' => "Public key not found, is not yet activated, or your access doesn't match with your info in our system."
        ];
        if (ApiHelper::publicCheckAccess($public_key, $website, $field, $request)) {
            $website = $website->where($field, $public_key)->first();
            $user = User::where('id', $website->user_id)->first();
            $user->password = bcrypt($request->get('password'));
            $user->save();
            Notification::send($user, new PublicChangePassword($website));
            $http_code = 200;
            $to_return = [
                'success' => 1
            ];
        }
        return response()->json($to_return, $http_code);
    }
    
}
