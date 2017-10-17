<?php

$authy_id = get_input('authy_id');

try {

	$user = new \ArckInteractive\TwilioAuthy\User();

	$user->requestCall($authy_id);

	return elgg_ok_response([
		'authy_id' => $authy_id,
	], elgg_echo('authy:phone_call:pending'));
} catch (RegistrationException $ex) {
	return elgg_error_response($ex->getMessage());
}