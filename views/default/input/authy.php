<?php

if (\ArckInteractive\TwilioAuthy\Auth::hasValidVerification()) {
	return;
}

$fields = [
	'authy_id',
	'authy_country_code',
	'authy_phone_number',
	'authy_email',
	'authy_signature',
	'authy_ts',
];

foreach ($fields as $field) {
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => $field,
		'value' => '',
	]);
}
?>
<script>
    require(['input/authy']);
</script>
