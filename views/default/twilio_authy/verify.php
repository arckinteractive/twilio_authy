<?php

$user = elgg_extract('entity', $vars);
if (!$user) {
	return;
}

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('twilio:authy:verification_code'),
	'#help' => elgg_echo('twilio:authy:verification_code:help'),
	'name' => 'verification_code',
]);

echo elgg_view_field([
	'#type' => 'submit',
]);

?>
<script>
    require(['twilio_authy/register'], function (ta) {
        ta.bind('verify');
    });
</script>

