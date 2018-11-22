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
            if ( !$website_host || !$referer_host || ($website_host && $referer_host && $website_host != $referer_host) ) {
                return false;
            }
        }
        return true;
    }

}