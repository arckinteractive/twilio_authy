<?php

return [

	'authy:settings:api_key' => 'Twilio Authy API Key',
	'authy:settings:2fa:actions' => 'Select action you would like to protect with Two-Factor authentication',
	'authy:settings:force_sms' => 'Force SMS',
	'authy:settings:force_sms:help' => 'Always send out an SMS, even if the user has an Authy app installed on their phone',

	'authy:email' => 'Email',
	'authy:phone' => 'Phone Number',
	'authy:token' => 'Confirmation Code',
	'authy:token:help' => '
		Please enter the code you have received via SMS. 
		Please allow some time for the message to arrive, and if it doesn\'t, %s instead
	',

	'authy:request_token:help' => '
        To help secure your account, we use two-factor authentication.
        Please enter a valid phone number, where you can receive SMS messages containing second factor authentication code.
    ',

	'authy:request_token_mask:help' => '
		To help secure your account, we use two-factor authentication.
        SMS message containing second factor authentication code will be sent to %s
	',

	'authy:error:register' => 'Failed to create a new authentication record: %s',
	'authy:error:request_token' => 'Failed to request an authentication code: %s',
	'authy:error:invalid_token' => 'The code you have entered is invalid',
	'authy:error:action_gatekeeper' => 'The action you are trying to perform requires two-factor authentication',
	'authy:error:invalid' => 'User not found',
	'authy:error:phone_call' => 'Unable to perform a voice call: %s',
	'authy:token_verified' => 'Thank you! You have been authenticated.',

	'authy:request_token' => 'Request Code',
	'authy:verify_token' => 'Confirm',

	'authy:2af:phone' => 'Two-Factor Authentication',
	'authy:error:update_failed' => 'Your phone number could not updated, please make sure it is in a correct format',
	'authy:error:update_succeeded' => 'You phone number was updated',

	'authy:admin:clear_storage' => 'Clear Storage',
	'authy:admin:clear_storage:confirm' => 'This operation will clear Authy user records. You should only use this if you have registered a new Authy app',

	'authy:request_call' => 'request a voice call',
	'authy:phone_call:pending' => 'You should be receiving a voice call with your confirmation code shortly',

	'authy:api_key:instructions' => 'Please note that Authy API key is not your Twilio API Key. In order to generate an Authy API key, go to All Products & Services in your Twilio console. Select Authy, create a new app or select an existing one. The key will be under the Settings of your application.',
];