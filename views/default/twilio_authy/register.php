<?php

$phone = (array)elgg_extract('auth_phone', $vars, []);

echo elgg_view_field([
    '#type' => 'fieldset',
    '#label' => elgg_echo('twilio:authy:phone'),
    '#help' => elgg_echo('twilio:authy:phone:help'),
    'align' => 'horizontal',
    'fields' => [
        [
            '#type' => 'text',
            'placeholder' => '+1',
            'name' => 'auth_phone[country_code]',
            'value' => elgg_extract('country_code', $phone),
            'size' => 4,
            'pattern' => '^[\+]\d{1,3}$',
            'required' => true,
        ],
        [
            '#type' => 'text',
            'placeholder' => '123-456-7890',
            'name' => 'auth_phone[cellphone]',
            'value' => elgg_extract('cellphone', $phone),
            'pattern' => '^\d{2,4}[\-]\d{2,4}[\-]\d{1,6}$',
			'required' => true,
        ],
    ],
]);

?>
<script>
    require(['twilio_authy/register'], function (ta) {
        ta.bind('start');
    });
</script>