<?php

namespace App\Http\Controllers\Api\Admin;

use App\BannedIP;
use App\BannedCountry;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\BanCountryPostRequest;
use App\Http\Requests\Api\Admin\BanIPPostRequest;
use App\Helpers\ApiHelper;
use App\Website;

class BanController extends Controller
{

    public function banIP(Website $website, BanIPPostRequest $request) {
        $to_return = [
            'success' => 0,
            'message' => 'Unauthorized access!'
        ];
        $http_code = 401;
        if (ApiHelper::isAdmin()) {
            $ip = $request->get('ip') ?? null;
            if ($ip && BannedIP::where('website_id', $website->id)->where('ip', $ip)->exists()) {
                $to_return = [
                    'success' => 0,
                    'message' => 'IP is already banned on this website.'
                ];
                $http_code = 202;
            } else if ($ip) {
                $banned_ip = BannedIP::create([
                    'ip' => $ip,
                    'website_id' => $website->id
                ]);
                $to_return = $banned_ip->toArray();
            }
        }
        return response()->json($to_return, $http_code);
    }

    public function banCountry(Website $website, BanCountryPostRequest $request) {
        $to_return = [
            'success' => 0,
            'message' => 'Unauthorized access!'
        ];
        $http_code = 401;
        if (ApiHelper::isAdmin()) {
            $country_name = $request->get('name') ?? null;
            if ($country_name && BannedCountry::where('website_id', $website->id)->whereRaw("UPPER(name) LIKE '%" . strtoupper($country_name) . "%'")->exists()) {
                $to_return = [
                    'success' => 0,
                    'message' => 'Country is already banned on this website.'
                ];
                $http_code = 202;
            } else if ($country_name) {
                $banned_Country = BannedCountry::create([
                    'name' => $country_name,
                    'website_id' => $website->id
                ]);
                $to_return = $banned_Country->toArray();
            }
        }
        return response()->json($to_return, $http_code);
    }
    
}
