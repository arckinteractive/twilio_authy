<?php

$authy_id = elgg_extract('authy_id', $vars);

echo elgg_view_field([
	'#type' => 'hidden',
	'id' => 'authy-id',
	'value' => $authy_id,
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('authy:token'),
	'#help' => elgg_echo('authy:token:help'),
	'id' => 'authy-token',
	'value' => '',
	'required' => true,
]);

$submit = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('authy:verify_token'),
]);

elgg_set_form_footer($submit);