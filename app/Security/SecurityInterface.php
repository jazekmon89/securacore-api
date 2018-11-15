<?php

namespace App\Security;

use App\Client;
use App\ContentSecurity;
use Illuminate\Http\Request;

Interface SecurityInterface {

	public function create();
	public function get(Client $client, $security_variation);
	public function delete($id, $security_variation);
	public function update(Client $client, $security_variation, Request $request);
	public function updateMainField(Client $client, $security_variation, $field_name);
	public function updateJSONField(Client $client, $security_variation, $field_name, $function_id, Request $request);

}