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
                $http_code = 200;
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
                    'message' => 'Country "' . $country_name . '" is already banned on this website.'
                ];
                $http_code = 202;
            } else if ($country_name) {
                $banned_Country = BannedCountry::create([
                    'name' => $country_name,
                    'website_id' => $website->id
                ]);
                $to_return = $banned_Country->toArray();
                $http_code = 200;
            }
        }
        return response()->json($to_return, $http_code);
    }

    public function deleteIPBan(Website $website, BannedIP $banned_ip) {
        $to_return = [
            'success' => 0,
            'message' => 'Unauthorized access!'
        ];
        $http_code = 401;
        if (ApiHelper::isAdmin()) {
            $banned_ip = BannedIP::where('id', $banned_ip->id)->where('website_id', $website->id);
            if ($banned_ip->exists()) {
                $banned_ip = $banned_ip->first();
                $ip = $banned_ip->ip;
                $banned_ip->delete();
                $to_return = [
                    'success' => 1,
                    'message' => "IP '$ip' has beend unbanned."
                ];
                $http_code = 200;
            } else {
                $to_return = [
                    'success' => 1,
                    'message' => "Failed to delete: IP '$ip' is not found."
                ];
                $http_code = 200;
            }
        }
        return response()->json($to_return, $http_code);
    }

    public function deleteCountryBan(Website $website, BannedCountry $banned_country) {
        $to_return = [
            'success' => 0,
            'message' => 'Unauthorized access!'
        ];
        $http_code = 401;
        if (ApiHelper::isAdmin()) {
            $banned_country = BannedCountry::where('id', $banned_country->id)->where('website_id', $website->id);
            if ($banned_country->exists()) {
                $banned_country = $banned_country->first();
                $country_name = $banned_country->name;
                $banned_country->delete();
                $to_return = [
                    'success' => 1,
                    'message' => "Country '$country_name' has beend unbanned."
                ];
                $http_code = 200;
            } else {
                $to_return = [
                    'success' => 1,
                    'message' => "Failed to delete: Country '$country_name' is not found."
                ];
                $http_code = 200;
            }
        }
        return response()->json($to_return, $http_code);
    }
    
}
