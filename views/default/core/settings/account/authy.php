<?php

$user = elgg_get_page_owner_entity();

if (!$user instanceof ElggUser) {
	return;
}

$title = elgg_echo('authy:2af:phone');
$content = elgg_view_field([
	'#type' => 'fieldset',
	'#label' => elgg_echo('authy:phone'),
	'align' => 'horizontal',
	'required' => true,
	'fields' => [
		[
			'name' => 'authy_country_code',
			'#type' => 'country_phone_code',
			'placeholder' => '+1',
			'required' => true,
			'value' => $user->getPrivateSetting(\ArckInteractive\TwilioAuthy\User::SETTING_COUNTRY_CODE),
		],
		[
			'name' => 'authy_phone_number',
			'#type' => 'text',
			'placeholder' => '123-456-7890',
			'required' => true,
			'value' => $user->getPrivateSetting(\ArckInteractive\TwilioAuthy\User::SETTING_PHONE_NUMBER),
		],
	],
]);

echo elgg_view_module('info', $title, $content);