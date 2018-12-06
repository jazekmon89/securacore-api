<?php

namespace App\Http\Controllers\Api\Publics;

use App\Events\ClientLogSubmitted;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Publics\LogStoreRequest;
use App\Http\Requests\Api\Publics\IndexFilterRequest;
use Illuminate\Support\Facades\Redis;
use App\Log;
use App\Website;

class LogController extends Controller
{

    public function index(IndexFilterRequest $request) {
        $field = 'public_key';
        $public_key = $request->get($field) ?? null;
        $to_return = [];
        $website = new Website();
        if (ApiHelper::publicCheckAccess($public_key, $website, $field, $request)) {
            $website = $website->where('public_key', $request->get('public_key'))->first();
            $per_page = $request->get('per_page') ?? 10;
            $page = $request->get('page') ?? 1;
            $logs = Log::where('website_id', $website->id);
            if ($logs->exists()) {
                $to_return = $logs->paginate($per_page, array('*'), 'page', $page)->toArray();
            }
        }
        return response()->json($to_return, 200);
    }

    public function store(LogStoreRequest $request) {
        $field = 'public_key';
        $http_code = 404;
        $to_return = [
            'success' => 0,
            'message' => 'Public key not found!'
        ];
        $public_key = $request->get($field) ?? null;
        $website = new Website();
        if (ApiHelper::publicCheckAccess($public_key, $website, $field, $request)) {
            $website = $website->where('public_key', $request->get('public_key'))->first();
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
            $referer = request()->server('HTTP_REFERER');
            $referer = !$referer ? request()->server('REMOTE_ADDR') : null;
            $log->referer_url = $referer;
            $log->website_id = $website->id;
            $log->save();
            event(new ClientLogSubmitted($log));
            // $redis = Redis::connection();
            // $redisEmit = Redis::publish('test-channel', $log->toArray());
            // dump('$redisEmit', $redisEmit);
            $http_code = 200;
            $to_return = $log->toArray();
        }
        return response()->json($to_return, $http_code);
    }
    
}
