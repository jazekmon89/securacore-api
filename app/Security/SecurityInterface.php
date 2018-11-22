<?php

namespace App\Security;

use App\Website;
use App\ContentSecurity;
use App\Http\Requests\Api\SecurityUpdate;

Interface SecurityInterface {

	public function create();
	public function get(Website $website, $security_model);
	public function delete($id, $security_model);
	public function update(Website $website, $security_model, SecurityUpdate $request);
	public function updateSingleField(Website $website, $security_model);
	public function updateJSONField(Website $website, $security_model, $field_name, $sub_field_id);

}