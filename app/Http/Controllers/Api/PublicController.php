<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Requests\Api\PublicKeyRequest;
use App\Http\Requests\Api\LogRequest;
use App\Http\Controllers\Controller;
use App\Log;
use App\User;
use App\Website;


class PublicController extends Controller
{

    public function storeLog(Website $website, LogRequest $request) {
        $public_key = $request->get('public_key') ?? null;
        $field = 'public_key';
        if (ApiHelper::publicCheckAccess($public_key, $website, $field, $request)) {
            $data = $request->all();
            if (isset($data['public_key'])) {
                unset($data['public_key']);
            }
            $log = new Log();
            foreach($data as $field => $value) {
                $log->{$field} = $value;
            }
            $log->save();
            return response()->json($log->toArray(), 200);
        }
        return response()->json([
            'error' => 'Public key not found!'
        ], 404);
    }

    public function checkPublicKey(PublicKeyRequest $request) {
        $public_key = $request->get('public_key') ?? null;
        $website = new Website();
        $field = 'public_key';
        if (ApiHelper::publicCheckAccess($public_key, $website, $field, $request)) {
            $website = $website->where($field, $public_key)->first();
            if ( !$website->is_activated ) {
                $website->is_activated = 1;
                $website->save();
            }
            return response()->json([
                'is_activated' => $website->is_activated
            ], 200);
        }
        return response()->json([
            'error' => 'Public key not found!'
        ], 404);
    }
    
}
