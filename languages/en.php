<?php

return [

	'twilio:settings:api_key' => 'Twilio Authy API Key',

	'twilio:authy:phone' => 'Phone Number',
    'twilio:authy:phone:help' => '
        To help secure your account, we use two-factor authentication.
        Please enter a valid phone number, where you can receive SMS messages containing second factor authentication code.
    ',

	'twilio:authy:verification_code' => 'Verification Code',
	'twilio:authy:verification_code:help' => 'Enter the verification code you have recevied via SMS or Authy App',

	'twilio:authy:fail_registration' => 'We require that all users provide and verify their phone number.',

];