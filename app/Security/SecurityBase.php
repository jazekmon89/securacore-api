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

	public function get(Client $client, $security_model) {
	    $security_model = $security_model->where('client_id', $client->id);
	    if( !$security_model->exists() ) {
	    	return [];
	    }
		return $security_model->first();
	}

	public function delete($id, $security_model) {
		$security_model->where('id', $id);
		if ( $security_model->exists() ) {
			$security_model->delete();
		}
		return [];
	}

	public function update(Client $client, $security_model, Request $request) {
		$security_model = $security_model->where('client_id', $client->id);
		if ( $security_model->exists() ) {
			$security_model = $security_model->first();
			foreach( $request->all() as $key => $value ) {
				if ( !empty($security_model->{$key}) && $security_model->{$key} != $value ) {
					$security_model->{$key} = $value;
				}
			}
			$security_model->save();
			return $security_model;
		}
		return [];
	}

	public function updateMainField(Client $client, $security_model, $field_name) {
		$security_model = $security_model->where('client_id', $client->id);
		if ( $security_model->exists() ) {
			$security_model = $security_model->first();
			$security_model->{$field_name} = intval(!$security_model->{$field_name});
			$security_model->save();
			return $security_model;
		}
		return [];
	}

	public function updateJSONField(Client $client, $security_model, $field_name, $function_id, Request $request) {
		$security_model = $security_model->where('client_id', $client->id);
		if ( $security_model->exists() ) {
			$security_model = $security_model->first();
			$json_value = json_decode($security_model->{$field_name});
			$json_value[$function_id] = $request->all() ?? [];
			$json_value = json_encode($json_value);
			$security_model->{$field_name} = $json_value;
			$security_model->save();
		}
		return $security_model;
	}
}

?>