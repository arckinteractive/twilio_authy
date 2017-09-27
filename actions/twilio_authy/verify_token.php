<?php

$id = get_input('id');
$token = get_input('token');

$user = new ElggUser();

$phone = new \ArckInteractive\TwilioAuthy\User($user);
$phone->setId($id);

$valid = $phone->verifyToken($token);

if ($valid) {

	elgg_get_session()->set('authy_verified', time());

	$ts = time();
	$signature = elgg_build_hmac([
		'ts' => $ts,
		'token' => $token,
	]);

	return elgg_ok_response([
		'ts' => $ts,
		'token' => $token,
		'signature' => $signature->getToken(),
	], elgg_echo('authy:token_verified'));
} else {
	return elgg_error_response(elgg_echo('authy:invalid_token'));
}