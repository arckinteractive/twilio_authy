<?php

$users = new ElggBatch('elgg_get_entities_from_private_settings', [
	'private_setting_names' => \ArckInteractive\TwilioAuthy\User::SETTING_ID,
	'limit' => 0,
]);

foreach ($users as $user) {
	/* @var $user ElggUser */
	$user->setPrivateSetting(\ArckInteractive\TwilioAuthy\User::SETTING_ID, null);
}