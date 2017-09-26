define(function (require) {

    var Ajax = require('elgg/Ajax');
    var elgg = require('elgg');
    var spinner = require('elgg/spinner');

    var ta = {
        bind: function (step) {

            $(document).off('.twilio', '.elgg-form-register');

            switch (step) {
                default :
                case 'start' :
                    $(document).on('submit.twilio', '.elgg-form-register', ta.startHandler);
                    break;

                case 'verify' :
                    $(document).on('submit.twilio', '.elgg-form-register', ta.verifyHandler);
                    break;
            }
        },
        startHandler: function (e) {
            e.preventDefault();

            var ajax = new Ajax();

            var $form = $(this);

            ajax.action('twilio/start', {
                data: {
                    data: ajax.objectify($form)
                },
                beforeSend: function () {
                    $form.find('[type="submit"]')
                        .prop('disabled', true)
                        .addClass('elgg-state-disabled');
                }
            }).then(function (response, statusText, jqXHR) {
                if (jqXHR.AjaxData.status == -1) {
                    $form.find('[type="submit"]')
                        .prop('disabled', false)
                        .removeClass('elgg-state-disabled');

                    return;
                }

                $form.html(response);
            });
        },
        verifyHandler: function (e) {
            e.preventDefault();

            var ajax = new Ajax();

            var $form = $(this);

            ajax.action('twilio/verify', {
                data: {
                    data: ajax.objectify($form)
                },
                beforeSend: function () {
                    $form.find('[type="submit"]')
                        .prop('disabled', true)
                        .addClass('elgg-state-disabled');
                }
            }).then(function (response, statusText, jqXHR) {
                if (jqXHR.AjaxData.status == -1) {
                    $form.find('[type="submit"]')
                        .prop('disabled', false)
                        .removeClass('elgg-state-disabled');

                    return;
                }

                spinner.start()
                elgg.forward('');
            });
        }
    };
});
