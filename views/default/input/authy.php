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
    'authy_token',
    'authy_allow_sms',
    'authy_allow_profile',
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
