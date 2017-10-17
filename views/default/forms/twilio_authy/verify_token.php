<?php

$authy_id = elgg_extract('authy_id', $vars);

echo elgg_view_field([
	'#type' => 'hidden',
	'id' => 'authy-id',
	'value' => $authy_id,
]);

$link = elgg_view('output/url', [
	'text' => elgg_echo('authy:request_call'),
	'href' => "action/twilio_authy/request_call?authy_id=$authy_id",
	'is_action' => true,
	'class' => 'authy-request-call',
]);

$link = elgg_format_element('strong', [], $link);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('authy:token'),
	'#help' => elgg_echo('authy:token:help', [$link]),
	'id' => 'authy-token',
	'value' => '',
	'required' => true,
]);

$submit = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('authy:verify_token'),
]);

elgg_set_form_footer($submit);