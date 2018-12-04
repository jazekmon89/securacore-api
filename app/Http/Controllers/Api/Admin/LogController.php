<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexFilterRequest;
use App\Http\Requests\Api\Publics\LogStoreRequest;
use App\Log;
use App\Website;

class LogController extends Controller
{

    public function index(IndexFilterRequest $request) {
        $to_return = [];
        $http_code = 401;
        if (ApiHelper::isAdmin()) {
            $per_page = $request->get('per_page') ?? 10;
            $page = $request->get('page') ?? 1;
            $to_return = Log::paginate($per_page, array('*'), 'page', $page)->toArray();
            $http_code = 200;
        }
        return response()->json($to_return, $http_code);
    }

    public function indexByWebsiteId(Website $website, IndexFilterRequest $request) {
        $to_return = [];
        $http_code = 401;
        if (ApiHelper::isAdmin()) {
            $per_page = $request->get('per_page') ?? 10;
            $page = $request->get('page') ?? 1;
            $log = Log::where('website_id', $website->id);
            $to_return = $log->paginate($per_page, array('*'), 'page', $page)->toArray();
            $http_code = 200;
        }
        return response()->json($to_return, $http_code);
    }
    
}
