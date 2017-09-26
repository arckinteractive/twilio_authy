<?php

$data = get_input('data');

$email = elgg_extract('email', $data);
$auth_phone = elgg_extract('auth_phone', $data);
$country_code = elgg_extract('country_code', $auth_phone);
$phone_number = elgg_extract('phone_number', $auth_phone);

$country_code = trim($country_code, '\+\r\n');
$phone_number = preg_replace('\D', '', $phone_number);

try {
	$api_key = elgg_get_plugin_setting('api_key', 'twilio_authy');
	if (!$api_key) {
		return elgg_error_response();
	}

	$api = new \Authy\AuthyApi($api_key);

	$api_user = $api->registerUser($email, $phone_number, $country_code);

	if ($api_user->ok()) {
		$id = $api_user->id();
		$data['auth_phone']['id'] = $id;

		$api->phoneVerificationStart($phone_number, $country_code, 'sms');
	} else {
		throw new RegistrationException(
			"Unable to create a new Twilio Authy user record: " . PHP_EOL
			. implode(PHP_EOL, $api_user->errors())
		);
	}

	elgg_get_session()->set('registration_data', $data);

	$response = elgg_view('twilio_authy/verify');

	return elgg_ok_response($response);
} catch (Exception $ex) {
	return elgg_error_response($ex->getMessage());
}