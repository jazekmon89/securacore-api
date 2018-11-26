<?php

namespace App\Http\Controllers\Api\Publics;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Publics\LogRequest;
use App\Log;
use App\Website;

class LogController extends Controller
{

    public function index(LogRequest $request) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $website = Website::where('public_key', $request->get('public_key'));
            $logs = Log::where('website_id', $website->id);
            if ($logs->exists()) {
                $to_return = $logs->paginate(10)->toArray();
            }
        }
        return response()->json($to_return, 200);
    }

    public function store(LogRequest $request) {
        $field = 'public_key';
        $public_key = $request->get($field) ?? null;
        if (ApiHelper::publicCheckAccess($public_key, new Website(), $field, $request)) {
            $data = $request->all();
            if ($request->has($field)) {
                unset($data[$field]);
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
