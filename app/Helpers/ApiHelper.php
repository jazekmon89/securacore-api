<?php

namespace App\Helpers;

use App\Website;

class ApiHelper {

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
            if ( !$website_host || !$referer_host || ($website_host && $referer_host && $website_host != $referer_host) && $user->exists() ) {
                return false;
            }
        }
        return true;
    }

    public static function publicLogAccess(Website $website) {
        $referer = request()->server('HTTP_REFERER');
        $referer = !$referer ? request()->server('REMOTE_ADDR') : null;
        if ( !empty($referer) ) {
            $website_parsed = parse_url( $website->url );
            $referer_host = !empty($referer_parsed['host']) ? $referer_parsed['host'] : (!empty($referer_parsed['path']) ? $referer_parsed['path'] : null);
            $website = Website::where('url', 'like', '%' . $referer_host . '%');
            if ( $website->exists() ) {
                $website = $website->first();
                $user = User::where('id', $website->user_id)->where('status', 1);
                if ( $user->exists() ) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function publicCheckAccess($key, $model, $field) {
        $referer = request()->server('HTTP_REFERER');
        $referer = !$referer ? request()->server('REMOTE_ADDR') : null;
        $referer_host = !empty($referer_parsed['host']) ? $referer_parsed['host'] : (!empty($referer_parsed['path']) ? $referer_parsed['path'] : null);
        $model = $model->where($field, $key);
        if ( $model->exists() ) {
            if ( isset($model->user_id) ) {
                $user = User::where('id', $model->user_id)->where('status', 1);
                if ( $user->exists() ) {
                    return true;
                }
            } else {
                return true;
            }
        }
        return false;
    }

}