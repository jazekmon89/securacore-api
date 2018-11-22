<?php

namespace App\Http\Controllers\Api\Users;

use App\Website;
use App\BannedIP;
use App\BannedCountry;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BannedIPRequest;
use App\Http\Requests\Api\BannedCountryRequest;
use App\Helpers\ApiHelper;


class BannedController extends Controller
{

    public function ip_check(Website $website, BannedIPRequest $request) {
        $ip = $request->get('ip') ?? null;
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $banned_ip = BannedIP::where('website_id', $website->id)
                ->where('ip', $ip);
            $to_return = [
                'ip' => $ip
            ];
            if ($banned_ip->exists()) {
                $to_return['is_banned'] = true;
            } else {
                $to_return['is_banned'] = false;
            }
        }
        return response()->json($to_return, 200);
    }

    public function country_check(Website $website, BannedCountryRequest $request) {
        $country_name = $request->get('name') ?? null;
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $banned_country = BannedCountry::where('website_id', $website->id)
                ->where('name', $country_name);
            $to_return = [
                'country_name' => $country_name
            ];
            if ($banned_country->exists()) {
                $to_return['is_banned'] = true;
            } else {
                $to_return['is_banned'] = false;
            }
        }
        return response()->json($to_return, 200);
    }
    
}
