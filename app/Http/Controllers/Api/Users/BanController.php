<?php

namespace App\Http\Controllers\Api\Users;

use App\BannedIP;
use App\BannedCountry;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BannedCountryGetRequest;
use App\Http\Requests\Api\BannedIPGetRequest;
use App\Helpers\ApiHelper;
use App\Website;

class BanController extends Controller
{

    public function getBannedIPs(Website $website, BannedIPGetRequest $request) {
        $to_return = [
            'success' => 0,
            'message' => 'Unauthorized access!'
        ];
        $start_date = $request->get('start_date') ?? null;
        $end_date = $request->get('end_date') ?? null;
        $per_page = $request->get('per_page') ?? 10;
        $page = $request->get('page') ?? 1;
        $http_code = 401;
        if (ApiHelper::canAccess()) {
            $banned_ips = BannedIP::where('website_id', $website->id);
            if ($start_date) {
                $banned_ips = $banned_ips->whereBetween('created_at', [$start_date, $end_date]);
            }
            $queries = $request->all();
            $to_return = $banned_ips->paginate($per_page, array('*'), 'page', $page)->appends($queries)->toArray();
            $http_code = 200;
        }
        return response()->json($to_return, $http_code);
    }

    public function getBannedCountries(Website $website, BannedCountryGetRequest $request) {
        $to_return = [
            'success' => 0,
            'message' => 'Unauthorized access!'
        ];
        $start_date = $request->get('start_date') ?? null;
        $end_date = $request->get('end_date') ?? null;
        $per_page = $request->get('per_page') ?? 10;
        $page = $request->get('page') ?? 1;
        $http_code = 401;
        if (ApiHelper::canAccess()) {
            $banned_ips = BannedCountry::where('website_id', $website->id);
            if ($start_date) {
                $banned_ips = $banned_ips->whereBetween('created_at', [$start_date, $end_date]);
            }
            $queries = $request->all();
            $to_return = $banned_ips->paginate($per_page, array('*'), 'page', $page)->appends($queries)->toArray();
            $http_code = 200;
        }
        return response()->json($to_return, $http_code);
    }
    
}
