<?php

namespace ArckInteractive\TwilioAuthy;

/**
 * @access private
 */
class Router {

	public static function route($segments) {
		elgg_ajax_gatekeeper();

		$page = array_shift($segments);

		switch ($page) {
			case 'request_token' :
				$form = elgg_view_form('twilio_authy/request_token');
				echo elgg_view_module('lightbox', elgg_echo('authy:2af:phone'), $form, [
					'class' => 'twilio-authy-lightbox',
				]);
				return true;

			case 'verify_token' :
				$form = elgg_view_form('twilio_authy/verify_token', [], [
					'authy_id' => array_shift($segments),
				]);
				echo elgg_view_module('lightbox', elgg_echo('authy:verify_token'), $form, [
					'class' => 'twilio-authy-lightbox',
				]);
				return true;
		}
	}

	public static function setPublicPages($hook, $type, $return) {
		$return[] = "twilio_authy/.*";
		return $return;
	}
}