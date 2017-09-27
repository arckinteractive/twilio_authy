define(function (require) {

    var elgg = require('elgg');
    var lightbox = require('elgg/lightbox');
    var $ = require('jquery');
    var Ajax = require('elgg/Ajax');
    var spinner = require('elgg/spinner');

    $(document).on('submit', 'form', function (e) {

        var $form = $(this);
        var $authyIdInput = $form.find('input[name="authy_id"]').last();
        var $authySignatureInput = $form.find('input[name="authy_signature"]').last();

        if ($authyIdInput.length === 0) {
            // Form does not require Twilio Authy verification
            return;
        }

        var authyId = $authyIdInput.val();
        var signature = $authySignatureInput.val();

        if (signature) {
            // Form has been verified, so we can continue
            return;
        } else if (authyId) {
            e.preventDefault();

            var $container = $('<div>').html($('<div>').addClass('elgg-ajax-loader'));

            lightbox.open({
                href: $container,
                inline: true,
                width: 300,
            });

            var ajax = new Ajax(false);
            ajax.path('twilio_authy/verify_token/' + authyId).done(function (output) {
                $container.html($(output));
                lightbox.resize();
                $container.find('form').on('submit', function (e) {
                    e.preventDefault();

                    var $authform = $(this);

                    var id = $authform.find('#authy-id').val();
                    var token = $authform.find('#authy-token').val();

                    var ajax = new Ajax(true);
                    ajax.action('twilio_authy/verify_token', {
                        data: {
                            id: id,
                            token: token
                        },
                        beforeSend: function () {
                            $authform.find('[type="submit"]')
                                .prop('disabled', true)
                                .addClass('elgg-input-disabled');
                        }

                    }).done(function (output, statusText, jqXHR) {
                        $form.find('input[name="authy_ts"]').val(output.ts);
                        $form.find('input[name="authy_signature"]').val(output.signature);

                        lightbox.close();

                        // Have to do this weird thing, because profile_manager
                        // does something to the registratin form
                        $form.find('[type="submit"]').last().trigger('click');
                    }).fail(function () {
                        $authform.find('[type="submit"]')
                            .prop('disabled', false)
                            .removeClass('elgg-input-disabled');
                        lightbox.close();
                    });
                });
            });
        } else {
            e.preventDefault();

            var authy_username, authy_password, remail;
            if ($form.is('.elgg-form-login')) {
                authy_username = $form.find('input[name="username"]').val();
                authy_password = $form.find('input[name="password"]').val();
            } else if ($form.is('.elgg-form-register')) {
                remail = $form.find('input[name="email"]').val();
            }

            var $container = $('<div>').html($('<div>').addClass('elgg-ajax-loader'));

            lightbox.open({
                href: $container,
                inline: true,
                width: 300,
            });

            var ajax = new Ajax(false);
            ajax.path('twilio_authy/request_token', {
                data: {
                    authy_username: authy_username,
                    authy_password: authy_password,
                    remail: remail,
                }
            }).done(function (output) {
                $container.html($(output));
                lightbox.resize();
                $container.find('form').on('submit', function (e) {
                    e.preventDefault();

                    var $authform = $(this);

                    var email = $authform.find('#authy-email').val();
                    var id = $authform.find('#authy-id').val();
                    var hash = $authform.find('#authy-hash').val();
                    var guid = $authform.find('#authy-guid').val();
                    var countryCode = $authform.find('#authy-country-code').val();
                    var phoneNumber = $authform.find('#authy-phone-number').val();

                    var ajax = new Ajax(true);
                    ajax.action('twilio_authy/request_token', {
                        data: {
                            authy_id: id,
                            authy_hash: hash,
                            authy_guid: guid,
                            email: email,
                            country_code: countryCode,
                            phone_number: phoneNumber
                        },
                        beforeSend: function () {
                            $authform.find('[type="submit"]')
                                .prop('disabled', true)
                                .addClass('elgg-input-disabled');
                        }
                    }).done(function (output, statusText, jqXHR) {
                        $form.find('input[name="authy_id"]').val(output.authy_id);
                        $form.find('input[name="authy_email"]').val(output.email);
                        $form.find('input[name="authy_country_code"]').val(output.country_code);
                        $form.find('input[name="authy_phone_number"]').val(output.phone_number);

                        $form.trigger('submit');
                    }).fail(function () {
                        $authform.find('[type="submit"]')
                            .prop('disabled', false)
                            .removeClass('elgg-input-disabled');

                        lightbox.close();
                    });
                });
            });

        }
    });
});