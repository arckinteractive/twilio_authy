<?php

try {
	$user = \ArckInteractive\TwilioAuthy\Auth::getUser();

	if (!$user) {
		$user = new ElggUser();
		$user->email = get_input('email');
	}

	$phone = new \ArckInteractive\TwilioAuthy\User($user);

	$authy_id = $phone->getId();

	if (!$authy_id) {
		$authy_id = get_input('authy_id');
	}

	if (!$authy_id) {
		$country_code = get_input('country_code');
		$phone_number = get_input('phone_number');

		$authy_id = $phone->setPhone($country_code, $phone_number);
	}

	if (!$authy_id) {
		return elgg_error_response(elgg_echo('authy:error:register'));
	}

	$requested = $phone->requestToken();
	if (!$requested) {
		return elgg_error_response(elgg_echo('authy:error:request_token'));
	}

	return elgg_ok_response([
		'authy_id' => $authy_id,
		'country_code' => $country_code,
		'phone_number' => $phone_number,
	]);
} catch (RegistrationException $ex) {
	return elgg_error_response($ex->getMessage());
}