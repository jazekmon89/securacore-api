<?php

namespace App\Security\Variations;

use App\Security\SecurityBase;
use App\Client;
use App\SecurityLabel;

class ContentProtection extends SecurityBase {

	public function get(Client $client, $security_variation) {
	    $security_variation->where('client_id', $client->id);
	    if( !$security_variation->exists() ) {
	    	return [];
	    }
	    $security_variation = $security_variation->first();
    	$functions = json_decode($security_variation->function, 1);
    	$full_functions = [
    		'enabled' => $security_variation->enabled,
    		'content_securities' => []
    	];
    	$content_security_label = SecurityLabel::whereIn( 'id', array_keys( $functions ) )->get();
    	foreach( $functions as $label_id => $status_alert ) {
    		if ( !empty($content_security_label[$label_id]) ) {
    			array_push( $full_functions['content_securities'], [
    				'name' => $content_security_label[$label_id]->name,
    				'message' => $content_security_label[$label_id]->message,
    				'enabled' => $status_alert['enabled'],
    				'alert' => $status_alert['alert']
    			]);
    		}
		}
		return $full_functions;
	}

}

?>