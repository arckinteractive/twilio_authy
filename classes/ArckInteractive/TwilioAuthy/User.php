<?php

namespace ArckInteractive\TwilioAuthy;

class User {

	const SETTING_PHONE_NUMBER = 'authy_phone_number';
	const SETTING_COUNTRY_CODE = 'authy_country_code';
	const SETTING_ID = 'authy_id';

	/**
	 * @var \ElggUser
	 */
	private $user;

	/**
	 * Constructor
	 *
	 * @param \ElggUser $user User entity
	 */
	public function __construct(\ElggUser $user = null) {
		$this->user = $user;
	}

	/**
	 * Build a new API instance
	 *
	 * @return \Authy\AuthyApi
	 */
	private function getApi() {
		$api_key = elgg_get_plugin_setting('api_key', 'twilio_authy');
		$api = new \Authy\AuthyApi($api_key);
		return $api;
	}

	/**
	 * Stores the ID of the user record recevied from Authy
	 *
	 * @param int $authy_id ID
	 * @return void
	 */
	public function setId($authy_id) {
		$this->user->setPrivateSetting(self::SETTING_ID, $authy_id);
	}

	/**
	 * Returns Authy ID of the user record
	 * @return int
	 */
	public function getId() {
		return $this->user->getPrivateSetting(self::SETTING_ID) ? : '';
	}

	/**
	 * Sets user's Authy phone and registers the user
	 *
	 * @param string $country_code Country code, e.g. +1
	 * @param string $phone_number Phone number, e.g. '025-555-3323'
	 *
	 * @return int Authy ID
	 *
	 * @throws \RegistrationException
	 */
	public function setPhone($country_code, $phone_number) {

		$filtered_country_code = preg_replace('/\D/i', '', $country_code);
		$filtered_phone_number = preg_replace('/\D/i', '', $phone_number);

		$api = $this->getApi();

		$api_user = $api->registerUser($this->user->email, $filtered_phone_number, $filtered_country_code);

		if (!$api_user->ok()) {
			throw new \RegistrationException(elgg_echo('authy:error:register', [
				implode(PHP_EOL, (array) $api_user->errors())
			]));
		}

		$id = $api_user->id();

		$this->setId($id);
		$this->user->setPrivateSetting(self::SETTING_COUNTRY_CODE, $country_code);
		$this->user->setPrivateSetting(self::SETTING_PHONE_NUMBER, $phone_number);

		if ($this->user->guid) {
			$ia = elgg_set_ignore_access(true);
			$this->user->save();
			elgg_set_ignore_access($ia);
		}

		return $id;
	}

	/**
	 * Returns phone country code
	 *
	 * @param bool $filter Normalize the output
	 * @return string|false
	 */
	public function getCountryCode($filter = false) {
		$country_code = $this->user->getPrivateSetting(self::SETTING_COUNTRY_CODE);
		if (!$country_code) {
			return '';
		}
		if ($filter) {
			return preg_replace('/\D/i', '', $country_code);
		}
		return $country_code;
	}

	/**
	 * Returns phone number
	 *
	 * @param bool $filter Normalize the output
	 * @return string|false
	 */
	public function getPhoneNumber($filter = false) {
		$phone_number = $this->user->getPrivateSetting(self::SETTING_PHONE_NUMBER);
		if (!$phone_number) {
			return '';
		}
		if ($filter) {
			return preg_replace('/\D/i', '', $phone_number);
		}
		return $phone_number;
	}

	/**
	 * Returns formatted number, e.g. +123456789
	 *
	 * @return string|false
	 */
	public function getFormattedNumber() {
		$country_code = $this->getCountryCode(true);
		$phone_number = $this->getPhoneNumber();

		if ($country_code && $phone_number) {
			return '+' . $country_code . ' ' . $phone_number;
		}

		return false;
	}

	/**
	 * Returns masked number
	 * @return string
	 */
	public function getMaskedNumber() {
		$mask = function($number, $chart = 'x') {
			return substr($number, 0, 3) . str_repeat($chart, strlen($number) - 8) . substr($number, -2);
		};

		return $mask($this->getFormattedNumber());
	}
	/**
	 * Request token via SMS
	 *
	 * @return bool
	 * @throws \RegistrationException
	 */
	public function requestToken() {
		$api = $this->getApi();

		$result = $api->requestSms($this->getId());

		if (!$result->ok()) {
			throw new \RegistrationException(elgg_echo('authy:error:request_token', [
				implode(PHP_EOL, (array) $result->errors())
			]));
		}

		return true;
	}

	/**
	 * Validate token provided by the user
	 *
	 * @param string $token Token
	 * @return bool
	 */
	public function verifyToken($token) {
		$api = $this->getApi();

		$result = $api->verifyToken($this->getId(), $token);

		return $result->ok();
	}


}