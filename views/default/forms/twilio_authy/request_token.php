<?php

$registration_email = get_input('remail');
$user = \ArckInteractive\TwilioAuthy\Auth::getUser();

if (!$user) {
	$user = new ElggUser();
	$user->email = $registration_email;
}

$phone = new \ArckInteractive\TwilioAuthy\User($user);
$authy_id = $phone->getId();

if ($authy_id) {
	echo elgg_format_element('div', [
		'class' => 'box elgg-status-success',
	], elgg_format_element('p', [], elgg_echo('authy:request_token_mask:help', [$phone->getMaskedNumber()])));

	echo elgg_view_field([
		'#type' => 'hidden',
		'id' => 'authy-id',
		'value' => $authy_id,
	]);

	echo elgg_view_field([
		'#type' => 'hidden',
		'id' => 'authy-guid',
		'value' => (int) $user->guid,
	]);

	echo elgg_view_field([
		'#type' => 'hidden',
		'id' => 'authy-hash',
		'value' => elgg_build_hmac([
			'authy_id' => (int) $authy_id,
			'authy_guid' => (int) $user->guid,
		])->getToken(),
	]);
} else {

	echo elgg_format_element('div', [
		'class' => 'box elgg-status-success',
	], elgg_format_element('p', [], elgg_echo('authy:request_token:help')));

	if (!$user->guid) {
		echo elgg_view_field([
			'id' => 'authy-email',
			'#type' => 'email',
			'#label' => elgg_echo('authy:email'),
			'required' => true,
			'value' => $registration_email,
		]);
	}

	echo elgg_view_field([
		'#type' => 'fieldset',
		'#label' => elgg_echo('authy:phone'),
		'align' => 'horizontal',
		'required' => true,
		'fields' => [
			[
				'id' => 'authy-country-code',
				'#type' => 'country_phone_code',
				'value' => '1',
				'required' => true,
			],
			[
				'id' => 'authy-phone-number',
				'#type' => 'text',
				'placeholder' => '123-456-7890',
				'required' => true,
			],
		],
	]);
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('authy:request_token'),
]);

elgg_set_form_footer($footer);