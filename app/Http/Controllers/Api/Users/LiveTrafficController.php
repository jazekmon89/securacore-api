<?php

namespace App\Http\Controllers\Api\Users;

use App\Website;
use App\LiveTraffic;
use App\Http\Requests\Api\LiveTrafficRequest;
use App\Http\Controllers\Controller;
use App\Helpers\ApiHelper;


class LiveTrafficController extends Controller
{

    public function index(Website $website, LiveTrafficRequest $request) {
        $ip = $request->get('ip') ?? null;
        $useragent = $request->get('useragent') ?? null;
        $date = $request->get('date') ?? null;
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $live_traffic = LiveTraffic::where('ip', $ip)
                ->where('useragent', 'like', '%' . $useragent . '%')
                ->where('date', $date);
            $to_return = $live_traffic->get()->toArray();
        }
        return response()->json($to_return, 200);
    }
    
}
