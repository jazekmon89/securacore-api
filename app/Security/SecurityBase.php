<?php

namespace App\Security;

use App\Client;
use App\SecurityLabel;
use App\Security\SecurityInterface;
use App\Variations\ContentSecurity;
use Illuminate\Http\Request;

class SecurityBase implements SecurityInterface {

	public function create() {
		// TODO: New security functions will be created here
		// but are we going to support any, soon?
	}

	public function get(Client $client, $security_variation) {
	    $security_variation->where('client_id', $client->id);
	    if( !$security_variation->exists() ) {
	    	return [];
	    }
		return $security_variation->first();
	}

	public function delete($id, $security_variation) {
		$security_variation->where('id', $id);
		if ( $security_variation->exists() ) {
			$security_variation->delete();
		}
		return [];
	}

	public function update(Client $client, $security_variation, Request $request) {
		$security_variation->where('client_id', $id);
		if ( $security_variation->exists() ) {
			$security_variation = $security_variation->first();
			foreach( $request->all() as $key => $value ) {
				if ( !empty($security_variation->{$key}) && $security_variation->{$key} != $value ) {
					$security_variation->{$key} = $value;
				}
			}
			$security_variation->save();
			return get_object_vars($security_variation);
		}
		return $security_variation;
	}

	public function updateMainField(Client $client, $security_variation, $field_name) {
		$security_variation->where('client_id', $client->id);
		if ( $security_variation->exists() ) {
			$security_variation = $security_variation->first();
			$security_variation->{$field_name} = intval(!$security_variation->{$field_name});
			$security_variation->save();
		}
		return $security_variation;
	}

	public function updateJSONField(Client $client, $security_variation, $field_name, $function_id, Request $request) {
		$security_variation->where('client_id', $client->id);
		if ( $security_variation->exists() ) {
			$security_variation = $security_variation->first();
			$json_value = json_decode($security_variation->{$field_name});
			$json_value[$function_id] = $request->all() ?? [];
			$json_value = json_encode($json_value);
			$security_variation->{$field_name} = $json_value;
			$security_variation->save();
		}
		return $security_variation;
	}
}

?>