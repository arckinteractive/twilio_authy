<?php

$value = elgg_extract('value', $vars);
if ($value) {
	$value = preg_replace('/\D/i', '', $value);
}

$options = elgg_extract('options_values', $vars);

if (empty($options)) {
	$options = [];

	$countries = elgg_get_country_info(['name', 'phone_code'], 'name');
	foreach ($countries as $country) {
		$code = $country['phone_code'];
		$name = $country['name'];
		$options["$code"] = "{$name} (+{$code})";
	}
}

$vars['value'] = $value;
$vars['options_values'] = $options;

$select = elgg_view('input/select', $vars);
echo elgg_format_element('span', [
	'class' => 'select',
], $select);