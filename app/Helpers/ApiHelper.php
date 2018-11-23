<?php

namespace App\Helpers;

use App\User;
use App\Website;

class ApiHelper {

    public static function compareHosts($referer_host, $host) {
        $referer_ip_flag = ip2long($referer_host) !== false;
        if ($referer_ip_flag) {
            $model_host = gethostbyname($host);
            if ( $model_host == $referer_host ) {
                return true;
            }
        } else if ( $referer_host == $host) {
            return true;
        }
        return false;
    }

	public static function canAccess() {
		$user = auth()->user();
		$website = Website::where('user_id', $user->id)->first();
        // for testing environments, let us give access
        if ( env('APP_ENV') != 'production' ){
            return true;
        }
        $referer = request()->server('HTTP_REFERER');
        $referer = !$referer ? request()->server('REMOTE_ADDR') : null;
        $referer_parsed = parse_url( $referer );
        $websites_flag = false;
        if ( !empty($referer) ) {
            $website_parsed = parse_url( $website->url );
            $website_host = !empty($website_parsed['host']) ? $website_parsed['host'] : (!empty($website_parsed['path']) ? $website_parsed['path'] : null);
            $referer_host = !empty($referer_parsed['host']) ? $referer_parsed['host'] : (!empty($referer_parsed['path']) ? $referer_parsed['path'] : null);
            $user = User::where('id', $website->user_id)->where('status', 1);
            $referer_flag = self::compareHosts($referer_host, $website_host);
            if ( !$website_host || !$referer_host || ($website_host && $referer_host && $referer_flag) && $user->exists() ) {
                return false;
            }
        }
        return true;
    }

    public static function publicCheckAccess($public_key, $model, $field, $request) {
        $user_exists = false;
        $has_user = false;
        $referer_flag = false;
        $has_url = false;
        $referer = $request->server('HTTP_REFERER');
        $referer = !$referer ? request()->server('REMOTE_ADDR') : null;
        $referer_parsed = parse_url( $referer );
        $referer_host = !empty($referer_parsed['host']) ? $referer_parsed['host'] : (!empty($referer_parsed['path']) ? $referer_parsed['path'] : null);
        $model = $model->where($field, $public_key);
        if ( $model->exists() ) {
            $model = $model->first();
            if ( isset($model->user_id) ) {
                $has_user = true;
                $user = User::where('id', $model->user_id)->where('status', 1);
                if ( $user->exists() ) {
                    $user_exists = true;
                }
            }
            if ( isset($model->url) ) {
                $has_url = true;
                $model_parsed = parse_url( $model->url );
                $model_host = !empty($model_parsed['host']) ? $model_parsed['host'] : (!empty($model_parsed['path']) ? $model_parsed['path'] : null);
                $referer_flag = self::compareHosts($referer_host, $model_host);
            }
            if ( $has_user && $user_exists && $has_url && $referer_flag ) {
                return true;
            } else if ( $has_user && !$user_exists || $has_url && !$referer_flag ) {
                return false;
            }
        }
        return false;
    }

}