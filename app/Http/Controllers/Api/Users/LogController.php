<?php

namespace App\Http\Controllers\Api\Users;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexFilterRequest;
use App\Log;
use App\Website;

class LogController extends Controller
{

    public function index(Website $website, IndexFilterRequest $request) {
        if (ApiHelper::canAccess()) {
            $logs = Log::where('website_id', $website->id);
            $per_page = $request->get('per_page') ?? 10;
            $page = $request->get('page') ?? 1;
            $to_return = $logs->paginate($per_page, array('*'), 'page', $page)->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function show(Website $website, Log $log) {
        return response()->json($log->getAttributes(), 200);
    }
    
}
