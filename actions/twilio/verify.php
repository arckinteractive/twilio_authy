<?php

try {
	$verification_code = get_input('verification_code');
	if (!$verification_code) {
		throw new RegistrationException("Please enter the verification code");
	}

	$data = elgg_get_session()->get('registration_data');

	$auth_phone = elgg_extract('auth_phone', $data);
	$country_code = elgg_extract('country_code', $auth_phone);
	$phone_number = elgg_extract('phone_number', $auth_phone);

	$country_code = trim($country_code, '\+\r\n');
	$phone_number = preg_replace('\D', '', $phone_number);

	$api_key = elgg_get_plugin_setting('api_key', 'twilio_authy');
	if (!$api_key) {
		return elgg_error_response();
	}

	$api = new \Authy\AuthyApi($api_key);

	$result = $api->phoneVerificationCheck($phone_number, $country_code, $verification_code);
	if (!$result->ok()) {
		throw new RegistrationException(
			"Unable to verify your phone: " . PHP_EOL
			. implode(PHP_EOL, $api_user->errors())
		);
	}

	elgg_get_session()->set('registration_data_verified', 'twilio');

	foreach ($data as $key => $value) {
		set_input($key, $value);
	}

	action('register');

} catch (Exception $ex) {
	return elgg_error_response($ex->getMessage());
}


