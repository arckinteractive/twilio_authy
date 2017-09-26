<?php

$entity = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('twilio:settings:api_key'),
	'name' => 'api_key',
	'value' => $entity->api_key,
	'required' => true,
]);