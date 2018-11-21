<?php

namespace App\Security;

use App\Client;
use App\SecurityLabel;
use App\Security\SecurityInterface;
use App\Variations\ContentSecurity;
use App\Http\Requests\Api\SecurityUpdate;

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

	public function update(Client $client, $security_model, SecurityUpdate $request) {
		$security_model = $security_model->where('client_id', $client->id);
		if ( $security_model->exists() ) {
			$security_model = $security_model->first();
			foreach( $request->all() as $key => $value ) {
				if ( isset($security_model->{$key}) && $security_model->{$key} != $value ) {
					$security_model->{$key} = $value;
					break;
				}
			}
			$security_model->save();
			return $security_model;
		}
		return [];
	}

	public function updateSingleField(Client $client, $security_model) {
		$field_name = $security_model::ACTIVATOR_FIELD;
		$security_model = $security_model->where('client_id', $client->id);
		if ( $security_model->exists() ) {
			$security_model = $security_model->first();
			$security_model->{$field_name} = intval(!$security_model->{$field_name});
			$security_model->save();
			return $security_model;
		}
		return [];
	}

	public function updateJSONField(Client $client, $security_model, $field_name, $sub_field_id) {
		$security_model = $security_model->where('client_id', $client->id);
		if ( $security_model->exists() ) {
			$security_model = $security_model->first();
			if ( isset($security_model->{$field_name}) ) {
				$json_value = json_decode($security_model->{$field_name});
				$json_value->$sub_field_id->enabled = intval(!$json_value->$sub_field_id->enabled);
				$json_value = json_encode($json_value);
				$security_model->{$field_name} = $json_value;
				$security_model->save();
				return $security_model;
			}
		}
		return [];
	}
}

?>