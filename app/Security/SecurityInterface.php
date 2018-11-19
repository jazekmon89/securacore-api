<?php

namespace App\Security;

use App\Client;
use App\ContentSecurity;
use App\Http\Requests\SecurityUpdate;

Interface SecurityInterface {

	public function create();
	public function get(Client $client, $security_model);
	public function delete($id, $security_model);
	public function update(Client $client, $security_model, SecurityUpdate $request);
	public function updateSingleField(Client $client, $security_model);
	public function updateJSONField(Client $client, $security_model, $field_name, $function_id, SecurityUpdate $request);

}