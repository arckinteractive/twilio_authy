<?php

if (!elgg_get_plugin_setting('2fa:hybridauth/login','twilio_authy')) {
	return;
}

echo elgg_view('input/authy');