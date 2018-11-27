<?php

namespace App\Http\Controllers\Api\Publics;

use App\BannedIP;
use App\BannedCountry;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Publics\BanCountryGetRequest;
use App\Http\Requests\Api\Publics\BanCountryPostRequest;
use App\Http\Requests\Api\Publics\BanIPGetRequest;
use App\Http\Requests\Api\Publics\BanIPPostRequest;
use App\Helpers\ApiHelper;
use App\Website;

class BanController extends Controller
{

    public function banIP(BanIPPostRequest $request) {
        $field = 'public_key';
        $public_key = $request->get($field) ?? null;
        $to_return = [];
        $website = new Website();
        if (ApiHelper::publicCheckAccess($public_key, $website, $field, $request)) {
            $website = $website->where($field, $public_key)->first();
            $banned_ip = new BannedIP();
            $fillables = $banned_ip->getFillable();
            $data = $request->all();
            if ($request->has($field)) {
                unset($data[$field]);
            }
            foreach($data as $field => $value) {
                if ( ($value || $value === 0) && in_array($field, $fillables) ) {
                    $banned_ip->{$field} = $value;
                }
            }
            if (!$request->has('website_id')) {
                $banned_ip->website_id = $website->id;
            }
            $banned_ip->save();
            $to_return = $banned_ip->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function banCountry(BanCountryPostRequest $request) {
        $field = 'public_key';
        $public_key = $request->get($field) ?? null;
        $to_return = [];
        $website = new Website();
        if (ApiHelper::publicCheckAccess($public_key, $website, $field, $request)) {
            $website = $website->where($field, $public_key)->first();
            $banned_country = new BannedCountry();
            $fillables = $banned_country->getFillable();
            $data = $request->all();
            if ($request->has($field)) {
                unset($data[$field]);
            }
            foreach($data as $field => $value) {
                if ( ($value || $value === 0) && in_array($field, $fillables) ) {
                    $banned_country->{$field} = $value;
                }
            }if (!$request->has('website_id')) {
                $banned_country->website_id = $website->id;
            }
            $banned_country->save();
            $to_return = $banned_country->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function ipCheck(BanIPGetRequest $request) {
        $field = 'public_key';
        $public_key = $request->get($field) ?? null;
        $ip = $request->get('ip') ?? null;
        $to_return = [];
        $website = new Website();
        if (ApiHelper::publicCheckAccess($public_key, $website, $field, $request)) {
            $website = $website->where($field, $public_key)->first();
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

    public function countryCheck(BanCountryGetRequest $request) {
        $field = 'public_key';
        $public_key = $request->get($field) ?? null;
        $country_name = $request->get('name') ?? null;
        $website = new Website();
        $to_return = [];
        $website = new Website();
        if (ApiHelper::publicCheckAccess($public_key, $website, $field, $request)) {
            $website = $website::where($field, $public_key)->first();
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
