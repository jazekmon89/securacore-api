<?php

namespace App\Security\Variations;

use App\Security\SecurityBase;
use App\Client;
use App\SecurityLabel;

class ContentProtection extends SecurityBase {

	public function get(Client $client, $security_variation) {
	    $security_variation = $security_variation->where('client_id', $client->id);
	    if( !$security_variation->exists() ) {
	    	return [];
	    }
	    $security_variation = $security_variation->first();
    	$functions = json_decode($security_variation->function, 1);
    	$full_functions = [
    		'enabled' => $security_variation->enabled,
    		'content_securities' => []
    	];
        $security_labels = SecurityLabel::whereIn('id', array_keys($functions))
            ->get()
            ->keyBy('id')
            ->toArray();
    	foreach( $functions as $label_id => $status_alert ) {
            $function_security_labels = !empty($security_labels[$label_id]) ? $security_labels[$label_id] : ['name'=>'','message'=>''];
			array_push( $full_functions['content_securities'], [
				'name' => $function_security_labels['name'],
				'message' => $function_security_labels['message'],
				'enabled' => $status_alert['enabled'],
				'alert' => $status_alert['alert']
			]);
		}
		return $full_functions;
	}

}

?>