<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ContentSecurity;
use App\ContentSecurityLabel;
use App\Client;

class ContentSecurityController extends Controller
{
    public function index() {

    }

    public function create() {

    }

    public function store() {

    }

    public function show($id) {
    	$referer = request()->server('HTTP_REFERER');
    	$referer_parsed = parse_url( $referer );
    	$content_security = ContentSecurity::where('client_id', $id)->first();
    	$clients = Client::where('id', $id);
    	if ( !$clients->exists() ) {
    		return response()->json([
    			'success' => 0,
    			'message' => 'Invalid request'
    		], 400);
    	}
    	$clients = $clients->get();
    	$clients_flag = false;
    	if ( $referer != null ) {
	    	foreach( $clients as $client ) {
	    		$client_url = parse_url( $client->url );
	    		if( $client_url['host'] == $referer_parsed['host'] ) {
	    			$clients_flag = true;
	    			break;
	    		}
	    	}
	    	if ( !$clients_flag ) {
	    		return response()->json([
	    			'success' => 0,
	    			'message' => 'Invalid request'
	    		], 400);
	    	}
	    }
    	$functions = json_decode($content_security->function, 1);
    	$full_functions = [];
    	$content_security_label = ContentSecurityLabel::whereIn( 'id', array_keys( $functions ) )->get();
    	foreach( $functions as $label_id => $status_alert ){
    		if ( !empty($content_security_label[$label_id]) ) {
    			array_push( $full_functions, [
    				'name' => $content_security_label[$label_id]->name,
    				'message' => $content_security_label[$label_id]->message,
    				'status' => $status_alert[0],
    				'alert' => $status_alert[1]
    			]);
    		}
    	}
    	return $full_functions;
    }

    public function edit() {

    }

    public function update() {

    }

    public function destroy() {

    }
}
