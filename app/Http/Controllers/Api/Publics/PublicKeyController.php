<?php

namespace App\Http\Controllers\Api\Publics;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Publics\PublicKeyRequest;
use App\Http\Requests\Api\Publics\ChangePasswordRequest;
use App\User;
use App\Website;
use Illuminate\Support\Facades\Notification;

class PublicKeyController extends Controller
{

    public function checkAndActivatePublicKey(PublicKeyRequest $request) {
        $field = 'public_key';
        $website = new Website();
        $public_key = $request->get($field) ?? null;
        if (ApiHelper::publicCheckAccess($public_key, $website, $field, $request)) {
            $website = $website->where($field, $public_key)->first();
            if ( !$website->is_activated ) {
                $website->is_activated = 1;
                $website->save();
            }
            return response()->json([
                'website_id' => $website->id,
                'is_activated' => $website->is_activated
            ], 200);
        }
        return response()->json([
            'error' => 'Public key not found!'
        ], 404);
    }

    public function changePassword(ChangePasswordRequest $request) {
        $field = 'public_key';
        $website = new Website();
        $public_key = $request->get($field) ?? null;
        if (ApiHelper::publicCheckAccess($public_key, $website, $field, $request)) {
            $user = User::where('id', $website->id)->first();
            $user->password = $request->get('password');
            $user->save();
            Notification::send($user, new AdminUserChangePassword($website));
            return response()->json([
                'success' => 1
            ], 200);
        }
        return response()->json([
            'error' => 'Public key not found!'
        ], 404);
    }
    
}
