<?php

namespace ArckInteractive\TwilioAuthy;

class Router {

	public static function route($segments) {
		elgg_ajax_gatekeeper();

		$page = array_shift($segments);

		switch ($page) {
			case 'request_token' :
				echo elgg_view_form('twilio_authy/request_token');
				return true;

			case 'verify_token' :
				echo elgg_view_form('twilio_authy/verify_token', [], [
					'authy_id' => array_shift($segments),
				]);
				return true;
		}
	}

	public static function setPublicPages($hook, $type, $return) {
		$return[] = "twilio_authy/.*";
		return $return;
	}
}