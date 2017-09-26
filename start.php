<?php

/**
 * Twilio Authy
 *
 * Two-factor SMS authentication via Twilio Authy
 *
 * @author    Ismayil Khayredinov <ismayil@arckinteractive.com>
 * @copyright Copyright (c) 2017, ArckInteractive LLC
 */

use ArckInteractive\TwilioAuthy\Auth;

require_once __DIR__ . '/autoloader.php';

elgg_register_event_handler('init', 'system', function () {

	elgg_register_action('twilio/start', __DIR__ . '/actions/twilio/start.php');
	elgg_register_action('twilio/verify', __DIR__ . '/actions/twilio/verify.php');

	elgg_register_plugin_hook_handler('register', 'user', [Auth::class, 'registerUser'], 100);

	elgg_extend_view('register/extend', 'twilio_authy/register', 100);
});