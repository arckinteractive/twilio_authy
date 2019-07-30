<?php

$value = elgg_extract('value', $vars);
if ($value) {
	$value = preg_replace('/\D/i', '', $value);
}

if (empty($options)) {
	$options = ['' => ''];

	$countries = \ArckInteractive\TwilioAuthy\Fixtures::getCountryCodes();

	foreach ($countries as $country) {
		$code = preg_replace('/\D/i', '', $country['phone_code']);
		$name = $country['name'];
		
		$options[] = [
			'text' => "{$name} (+{$code})",
			'value' => $code,
			'selected' => $value === $code,
		];
	}
}

$vars['value'] = $value;
$vars['options'] = $options;

$select = elgg_view('input/select', $vars);
echo elgg_format_element('span', [
	'class' => 'select',
], $select);