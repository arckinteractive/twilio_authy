<?php

namespace ArckInteractive\TwilioAuthy;


class Fixtures {

	static $codes;

	public static function getCountryCodes() {
		if (!isset(self::$codes)) {
			$path = elgg_get_plugins_path() . 'twilio_authy/fixtures/dial-codes.json';
			$json = file_get_contents($path);

			self::$codes = json_decode($json, true);
		}

		return self::$codes;
	}
}