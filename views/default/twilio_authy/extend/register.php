<?php

if (!elgg_get_plugin_setting('2fa:register','twilio_authy')) {
	return;
}

echo elgg_view('input/authy');