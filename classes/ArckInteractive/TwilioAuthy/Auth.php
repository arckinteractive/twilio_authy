<?php

namespace ArckInteractive\TwilioAuthy;

use ElggUser;

class Auth {

	const SETTING_PHONE_NUMBER = 'twilio_authy_phone_number';
	const SETTING_COUNTRY_CODE = 'twilio_authy_country_code';
	const SETTING_ID = 'twilio_authy_id';

	/**
	 * Store user number provided on registration
	 *
	 * @param string   $event "create"
	 * @param string   $type  "user"
	 * @param ElggUser $user  User
	 *
	 * @return void
	 *
	 * @throws \RegistrationException
	 */
	public static function registerUser($event, $type, $user) {

		$auth_phone = get_input('auth_phone');
		if (!$auth_phone) {
			throw new \RegistrationException(elgg_echo('twilio:authy:fail_registration'));
		}

		$id = elgg_extract('id', $auth_phone);
		$country_code = elgg_extract('country_code', $auth_phone);
		$phone_number = elgg_extract('phone_number', $auth_phone);

		$user->setPrivateSetting(self::SETTING_ID, $id);
		$user->setPrivateSetting(self::SETTING_COUNTRY_CODE, $country_code);
		$user->setPrivateSetting(self::SETTING_PHONE_NUMBER, $phone_number);

		if (elgg_get_session()->get('registration_data_verified')) {
			elgg_set_user_validation_status($user->guid, true, 'twilio');
		}
	}

}