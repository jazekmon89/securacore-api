<?php

namespace App\Security;

use App\Website;
use App\SecurityLabel;
use App\Security\SecurityInterface;
use App\Variations\ContentSecurity;
use App\Http\Requests\Api\SecurityUpdate;

class SecurityBase implements SecurityInterface {

	public function create() {
		// TODO: New security functions will be created here
		// but are we going to support any, soon?
	}

	public function get(Website $website, $security_model) {
	    $security_model = $security_model->where('website_id', $website->id);
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

	public function update(Website $website, $security_model, SecurityUpdate $request) {
		$security_model = $security_model->where('website_id', $website->id);
		if ( $security_model->exists() ) {
			$security_model = $security_model->first();
			$fillables = $security_model->getFillable();
			foreach( $request->all() as $field_name => $value ) {
				if ( isset($security_model->{$field_name}) && $security_model->{$field_name} != $value && in_array($field_name, $fillables) ) {
					$security_model->{$field_name} = $value;
					break;
				}
			}
			$security_model->save();
			return $security_model;
		}
		return [];
	}

	public function updateSingleField(Website $website, $security_model) {
		$field_name = $security_model::ACTIVATOR_FIELD;
		$security_model = $security_model->where('website_id', $website->id);
		if ( $security_model->exists() ) {
			$security_model = $security_model->first();
			$fillables = $security_model->getFillable();
			if ( in_array($field_name, $fillables) ) {
				$security_model->{$field_name} = intval(!$security_model->{$field_name});
				$security_model->save();
			}
			return $security_model;
		}
		return [];
	}

	public function updateJSONField(Website $website, $security_model, $field_name, $sub_field_id) {
		$security_model = $security_model->where('website_id', $website->id);
		if ( $security_model->exists() ) {
			$security_model = $security_model->first();
			$fillables = $security_model->getFillable();
			if ( isset($security_model->{$field_name}) && in_array($field_name, $fillables) ) {
				$json_value = json_decode($security_model->{$field_name});
				$json_value->{$sub_field_id}->enabled = intval(!$json_value->{$sub_field_id}->enabled);
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