<?php

namespace App\Helpers;

use App\Client;

class ApiHelper {

	public static function canAccess() {
		$user = auth()->user();
		$client = Client::where('user_id', $user->id)->first();
        // for testing environments, let us give access
        if ( env('APP_ENV') != 'production' ){
            return true;
        }
        $referer = request()->server('HTTP_REFERER');
        $referer = !$referer ? request()->server('REMOTE_ADDR') : null;
        $referer_parsed = parse_url( $referer );
        $clients_flag = false;
        if ( !empty($referer) ) {
            $client_parsed = parse_url( $client->url );
            $client_host = !empty($client_parsed['host']) ? $client_parsed['host'] : (!empty($client_parsed['path']) ? $client_parsed['path'] : null);
            $referer_host = !empty($referer_parsed['host']) ? $referer_parsed['host'] : (!empty($referer_parsed['path']) ? $referer_parsed['path'] : null);
            if ( !$client_host || !$referer_host || ($client_host && $referer_host && $client_host != $referer_host) ) {
                return false;
            }
        }
        return true;
    }

}