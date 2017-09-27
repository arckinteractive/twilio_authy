# Twilio Authy for Elgg

![Elgg 2.3](https://img.shields.io/badge/Elgg-2.3-orange.svg?style=flat-square)

## Features

 * Provides Two-Factor Authentication for Login, Registration and User Settings forms
 * Can be used as a Captcha on other forms
 

## Usage

To add a captcha-like protection to your forms:

```php
// in your form view
echo elgg_view('input/authy');

// in your action or page handler
twilio_authy_gatekeeper();
```