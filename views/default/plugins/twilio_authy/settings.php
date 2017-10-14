<?php

elgg_register_menu_item('title', [
	'name' => 'clear_storage',
	'href' => 'action/twilio_authy/clear_storage',
	'text' => elgg_echo('authy:admin:clear_storage'),
	'is_action' => true,
	'confirm' => elgg_echo('authy:admin:clear_storage:confirm'),
	'link_class' => 'elgg-button elgg-button-action',
]);

$entity = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('authy:settings:api_key'),
	'name' => 'params[api_key]',
	'value' => $entity->api_key,
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('authy:settings:force_sms'),
	'#help' => elgg_echo('authy:settings:force_sms:help'),
	'name' => 'params[force_sms]',
	'value' => $entity->force_sms,
	'options' => [
		false => elgg_echo('option:no'),
		true => elgg_echo('option:yes'),
	]
]);

$actions = \ArckInteractive\TwilioAuthy\Auth::$actions;

$fields = [];
foreach ($actions as $action) {
	$fields[] = [
		'#type' => 'checkbox',
		'value' => 1,
		'default' => 0,
		'label' => $action,
		'name' => "params[2fa:$action]",
		'checked' => (bool) $entity->{"2fa:$action"},
	];
}

echo elgg_view_field([
	'#type' => 'fieldset',
	'#label' => elgg_echo("authy:settings:2fa:actions"),
	'fields' => $fields,
]);