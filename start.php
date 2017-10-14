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
use ArckInteractive\TwilioAuthy\Router;

require_once __DIR__ . '/autoloader.php';

elgg_register_event_handler('init', 'system', function () {

	elgg_register_page_handler('twilio_authy', [Router::class, 'route']);
	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', [Router::class, 'setPublicPages']);

	elgg_register_action('twilio_authy/request_token', __DIR__ . '/actions/twilio_authy/request_token.php', 'public');
	elgg_register_action('twilio_authy/verify_token', __DIR__ . '/actions/twilio_authy/verify_token.php', 'public');

	elgg_register_action('twilio_authy/clear_storage', __DIR__ . '/actions/twilio_authy/clear_storage.php', 'admin');

	foreach (Auth::$actions as $action) {
		elgg_extend_view("forms/$action", "twilio_authy/extend/$action");
		elgg_register_plugin_hook_handler('action', $action, [Auth::class, 'enforceTwoFactorAuth']);
	}

	elgg_register_plugin_hook_handler('register', 'user', [Auth::class, 'setUserPhone'], 100);
	elgg_register_plugin_hook_handler('usersettings:save', 'user', [Auth::class, 'updateUserPhone']);

	elgg_extend_view('forms/account/settings', 'core/settings/account/authy');
});

/**
 * Validate that Twilio Authy verification was successful
 * on the previous page and redirect user back if not
 *
 * @param bool $check_validity Check if previous verification has been performed within the last hour
 *                             and let the user through if so
 * @return void
 */
function twilio_authy_gatekeeper($check_validity = true) {

	if (Auth::hasValidVerification()) {
		return;
	}

	if (!Auth::gate()) {
		register_error(elgg_echo('authy:error:action_gatekeeper'));
		forward(REFERER);
	}
}