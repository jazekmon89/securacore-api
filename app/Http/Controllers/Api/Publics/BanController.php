<?php

namespace App\Http\Controllers\Api\Publics;

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

    public function banIP(BanIPPostRequest $request) {
        $website = new Website();
        $public_key = $request->get('public_key') ?? null;
        $to_return = [];
        if (ApiHelper::publicCheckAccess($public_key, $website, 'public_key', $request)) {
            $banned_ip = new BannedIP();
            $fillables = $banned_ip->getFillable();
            $data = $request->all();
            if ($request->has('public_key')) {
                unset($data['public_key']);
            }
            foreach($data as $field => $value) {
                if ( ($value || $value === 0) && in_array($field, $fillables) ) {
                    $banned_ip->{$field} = $value;
                }
            }
            $banned_ip->save();
            $to_return = $banned_ip->getAttributes();
        }
        return response()->json($to_return, 200);
    }

    public function banCountry(Website $website, BanCountryPostRequest $request) {
        $website = new Website();
        $public_key = $request->get('public_key') ?? null;
        $to_return = [];
        if (ApiHelper::publicCheckAccess($public_key, $website, 'public_key', $request)) {
            $banned_country = new BannedCountry();
            $fillables = $banned_country->getFillable();
            $data = $request->all();
            if ($request->has('public_key')) {
                unset($data['public_key']);
            }
            foreach($data as $field => $value) {
                if ( ($value || $value === 0) && in_array($field, $fillables) ) {
                    $banned_country->{$field} = $value;
                }
            }
            $banned_country->save();
            $to_return = $banned_country->getAttributes();
        }
        return response()->json($to_return, 200);
    }

    public function ipCheck(Website $website, BanIPGetRequest $request) {
        $website = new Website();
        $public_key = $request->get('public_key') ?? null;
        $ip = $request->get('ip') ?? null;
        $to_return = [];
        if (ApiHelper::publicCheckAccess($public_key, $website, 'public_key', $request)) {
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
        $website = new Website();
        $public_key = $request->get('public_key') ?? null;
        $country_name = $request->get('name') ?? null;
        $to_return = [];
        if (ApiHelper::publicCheckAccess($public_key, $website, 'public_key', $request)) {
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
