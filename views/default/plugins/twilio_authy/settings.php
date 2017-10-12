<?php

$entity = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('authy:settings:api_key'),
	'name' => 'params[api_key]',
	'value' => $entity->api_key,
	'required' => true,
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