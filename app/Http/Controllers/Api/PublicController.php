<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Requests\Api\PublicKeyRequest;
use App\Http\Controllers\Controller;
use App\User;
use App\Website;


class PublicController extends Controller
{

    public function storeLog(Website $website, LogRequest $request) {
        $to_return = [];
        if (ApiHelper::publicLogAccess($website)) {
            $data = $request->all();
            $log = new Log();
            foreach($data as $field => $value) {
                $log->{$field} = $value;
            }
            $log->save();
            $to_return = $log->get()->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function checkPublicKey(PublicKeyRequest $request) {
        $public_key = $request->get('public_key') ?? null;
        $website = new Website();
        $field = 'public_key';
        if (ApiHelper::publicCheckAccess($public_key, $website, $field)) {
            $website = $website->where($field, $public_key)->first();
            $user = User::where('id', $website->user_id);
            if ( !$website->is_activated ) {
                $website->is_activated = 1;
                $website->save();
            }
            return response()->json([
                'is_activated' => $website->is_activated
            ], 200);
        } else {
            return response()->json([
                'error' => 'Public key not found!'
            ], 404);
        }
    }
    
}
