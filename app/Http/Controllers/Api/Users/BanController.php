<?php

namespace App\Http\Controllers\Api\Users;

use App\Website;
use App\BannedIP;
use App\BannedCountry;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BanIPGetRequest;
use App\Http\Requests\Api\BanIPPostRequest;
use App\Http\Requests\Api\BanCountryGetRequest;
use App\Http\Requests\Api\BanCountryPostRequest;
use App\Helpers\ApiHelper;


class BanController extends Controller
{

    public function banIP(Website $website, BanIPPostRequest $request) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $data = $request->all();
            $banned_ip = new BannedIP();
            $request = $request->all();
            foreach($data as $field => $value) {
                $banned_ip->{$field} = $value;
            }
            $banned_ip->save();
            $to_return = $banned_ip->getAttributes();
        }
        return response()->json($to_return, 200);
    }

    public function banCountry(Website $website, BanCountryPostRequest $request) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $data = $request->all();
            $banned_country = new BannedCountry();
            $request = $request->all();
            foreach($data as $field => $value) {
                $banned_country->{$field} = $value;
            }
            $banned_country->save();
            $to_return = $banned_country->getAttributes();
        }
        return response()->json($to_return, 200);
    }

    public function ipCheck(Website $website, BanIPGetRequest $request) {
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

    public function countryCheck(Website $website, BanCountryGetRequest $request) {
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
