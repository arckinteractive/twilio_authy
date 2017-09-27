<?php

namespace ArckInteractive\TwilioAuthy;

use ElggUser;

/**
 * @access private
 */
class Auth {

	const TWILIO_VERIFICATION_VALIDITY = 86400;

	public static $actions = [
		'register',
		'login',
		'usersettings/save',
	];

	/**
	 * Enforce 2FA for actions
	 *
	 * @param string $hook        "action"
	 * @param string $action_name action name
	 *
	 * @return bool
	 */
	public static function enforceTwoFactorAuth($hook, $action_name) {

		if (self::hasValidVerification()) {
			return;
		}

		if (!elgg_get_plugin_setting("2fa:$action_name", 'twilio_authy')) {
			return;
		}

		if (!self::gate()) {
			register_error(elgg_echo('authy:error:action_gatekeeper'));

			return false;
		}
	}

	/**
	 * Validate the signature
	 * @return bool
	 */
	public static function gate() {
		$ts = (int) get_input('authy_ts');
		$token = get_input('authy_token');
		$signature = get_input('authy_signature');

		return elgg_build_hmac([
			'ts' => $ts,
			'token' => $token,
		])->matchesToken($signature);
	}

	/**
	 * Check if the session has already been verified within the last hour
	 * @return bool
	 */
	public static function hasValidVerification() {
		// Whenever user verifies their phone, we will allow 1hour for other actions
		$previous_verification = elgg_get_session()->get('authy_verified');
		if ($previous_verification && time() - $previous_verification < self::TWILIO_VERIFICATION_VALIDITY) {
			return true;
		}

		return false;
	}

	/**
	 * Get user from input
	 * @return ElggUser|false
	 */
	public static function getUser() {

		$get_user = function () {

			$user = elgg_get_logged_in_user_entity();
			if ($user) {
				return $user;
			}

			$username = get_input('authy_username', '');
			if ($username && strpos($username, '@') !== false && ($users = get_user_by_email($username))) {
				$username = $users[0]->username;
			}
			$password = get_input('authy_password', '');
			if ($username && $password && elgg_authenticate($username, $password) === true) {
				$user = get_user_by_username($username);
				if ($user) {
					return $user;
				}
			}

			$authy_id = (int) get_input('authy_id');
			$authy_hash = get_input('authy_hash');
			$authy_guid = (int) get_input('authy_guid');

			if ($authy_id && $authy_hash && $authy_guid) {
				$hmac = elgg_build_hmac([
					'authy_id' => $authy_id,
					'authy_guid' => $authy_guid,
				]);

				if ($hmac->matchesToken($authy_hash)) {
					$user = get_entity($authy_guid);
					if ($user) {
						return $user;
					}
				}
			}

			return false;
		};

		$ia = elgg_set_ignore_access(true);
		$ha = access_get_show_hidden_status();
		access_show_hidden_entities(true);

		$user = $get_user();

		access_show_hidden_entities($ha);
		elgg_set_ignore_access($ia);

		return $user;

	}

	public static function setUserPhone($hook, $type, $return, $params) {
		$user = elgg_extract('user', $params);

		$country_code = get_input('authy_country_code');
		$phone_number = get_input('authy_phone_number');

		try {
			if ($country_code && $phone_number && self::gate()) {
				$phone = new User($user);
				$phone->setPhone($country_code, $phone_number);
				elgg_set_user_validation_status($user->guid, true, 'twilio_authy');
			}
		} catch (\RegistrationException $e) {

		}

	}

	public static function updateUserPhone() {
		$user_guid = get_input('guid');
		$country_code = get_input('authy_country_code');
		$phone_number = get_input('authy_phone_number');

		if (!$country_code || !$phone_number) {
			return false;
		}

		if ($user_guid) {
			$user = get_user($user_guid);
		} else {
			$user = elgg_get_logged_in_user_entity();
		}

		try {
			if ($country_code && $phone_number) {
				$phone = new User($user);
				$phone->setPhone($country_code, $phone_number);
			}
			//system_message(elgg_echo('authy:error:update_succeeded'));
			return true;
		} catch (\RegistrationException $e) {
			register_error(elgg_echo('authy:error:update_failed'));
			return false;
		}
	}
}