<?php

return [

	'authy:settings:api_key' => 'Twilio Authy API Key',
	'authy:settings:2fa:actions' => 'Select action you would like to protect with Two-Factor authentication',

	'authy:email' => 'Email',
	'authy:phone' => 'Phone Number',
	'authy:token' => 'Confirmation Code',
	'authy:token:help' => '
		Please enter the code you have received via SMS. 
		Please allow some time for the message to arrive, and if it doesn\'t, close this dialog and resubmit the form
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
	'authy:token_verified' => 'Thank you! You have been authenticated.',

	'authy:request_token' => 'Request Code',
	'authy:verify_token' => 'Confirm',

	'authy:2af:phone' => 'Two-Factor Authentication',
	'authy:error:update_failed' => 'Your phone number could not updated, please make sure it is in a correct format',
	'authy:error:update_succeeded' => 'You phone number was updated',

];