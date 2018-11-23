<?php

namespace App\Http\Controllers\Api\Publics;

use App\Website;
use App\Log;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LogRequest;


class LogController extends Controller
{

    public function store(LogRequest $request) {
    	$website = new Website();
        $public_key = $request->get('public_key') ?? null;
        $field = 'public_key';
        if (ApiHelper::publicCheckAccess($public_key, $website, $field, $request)) {
            $data = $request->all();
            if ($request->has('public_key')) {
                unset($data['public_key']);
            }
            $log = new Log();
            $fillables = $log->getFillable();
            foreach($data as $field => $value) {
                if ( ($value || $value === 0) && in_array($field, $fillables) ) {
                    $log->{$field} = $value;
                }
            }
            $log->save();
            return response()->json($log->toArray(), 200);
        }
        return response()->json([
            'error' => 'Public key not found!'
        ], 404);
    }
    
}
