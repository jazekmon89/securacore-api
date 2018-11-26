<?php

namespace App\Http\Controllers\Api\Users;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LogRequest;
use App\Log;
use App\Website;

class LogController extends Controller
{

    public function index(Website $website) {
        if (ApiHelper::canAccess()) {
            $logs = Log::where('website_id', $website->id);
            $to_return = $logs->paginate(10)->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function show(Website $website, Log $log) {
        return response()->json($log->getAttributes(), 200);
    }
    
}
