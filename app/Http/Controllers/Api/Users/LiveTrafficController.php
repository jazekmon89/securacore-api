<?php

namespace App\Http\Controllers\Api\Users;

use App\Website;
use App\LiveTraffic;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LiveTrafficRequest;

class LiveTrafficController extends Controller
{

    public function index(Website $website, LiveTrafficRequest $request) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $per_page = $request->get('per_page') ?? 10;
            $page = $request->get('page') ?? 1;
            $live_traffic = new LiveTraffic();
            $fillables = $live_traffic->getFillable();
            array_push($fillables, 'created_at', 'updated_at');
            foreach ($request->all() as $field => $value) {
                if (in_array($field, $fillables)) {
                    $live_traffic = $live_traffic->where($field, $value);
                }
            }
            if (!$request->has('website_id')) {
                $live_traffic = $live_traffic->where('website_id', $website->id);
            }
            $to_return = $live_traffic->paginate($per_page, array('*'), 'page', $page)->toArray();
        }
        return response()->json($to_return, 200);
    }
    
}
