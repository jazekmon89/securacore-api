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
        $to_return = [];
        $field = 'public_key';
        $public_key = $request->get($field) ?? null;
        $website = new Website();
        if (ApiHelper::publicCheckAccess($public_key, $website, $field, $request)) {
            $website = $website->where($field, $public_key)->first();
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

    public function store(LiveTrafficPostRequest $request) {
        $field = 'public_key';
        $public_key = $request->get($field) ?? null;
        $website = new Website();
        if (ApiHelper::publicCheckAccess($public_key, $website, $field, $request)) {
            $website = $website->where($field, $public_key)->first();
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
            if (!$request->has('website_id')) {
                $live_traffic->website_id = $website->id;
            }
            $live_traffic->save();
            return response()->json($live_traffic->toArray(), 200);
        }
        return response()->json([
            'error' => 'Public key not found!'
        ], 404);
    }
    
}
