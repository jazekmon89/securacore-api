<?php

namespace App\Http\Controllers\Api\Publics;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Publics\LiveTrafficGetRequest;
use App\Http\Requests\Api\Publics\LiveTrafficPostRequest;
use App\LiveTraffic;
use App\User;
use App\Website;

class LiveTrafficController extends Controller
{

    public function index(LiveTrafficGetRequest $request) {
        $field = 'public_key';
        $public_key = $request->get($field) ?? null;
        $ip = $request->get('ip') ?? null;
        $useragent = $request->get('useragent') ?? null;
        $date = $request->get('date') ?? null;
        $to_return = [];
        if (ApiHelper::publicCheckAccess($public_key, new Website(), $field, $request)) {
            $live_traffic = LiveTraffic::where('ip', $ip)
                ->where('useragent', 'like', '%' . $useragent . '%')
                ->where('date', $date);
            $to_return = $live_traffic->paginate(10)->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function store(LiveTrafficPostRequest $request) {
        $field = 'public_key';
        $public_key = $request->get($field) ?? null;
        if (ApiHelper::publicCheckAccess($public_key, new Website(), $field, $request)) {
            $data = $request->all();
            if ($request->has($field)) {
                unset($data[$field]);
            }
            $live_traffic = new LiveTraffic();
            $fillables = $live_traffic->getFillable();
            foreach($data as $field => $value) {
                if ( ($value || $value === 0) && in_array($field, $fillables) ) {
                    $live_traffic->{$field} = $value;
                }
            }
            $live_traffic->save();
            return response()->json($live_traffic->toArray(), 200);
        }
        return response()->json([
            'error' => 'Public key not found!'
        ], 404);
    }
    
}
