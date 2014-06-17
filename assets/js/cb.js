/**
 * ClassByte JavaScript API
 * @author Rw
 * @year 2014
 * @version 1.0
 * License: Not for public use
 */
var CB = (function($) {
    var r = {};

    /**
     * Private
     */
    var cb_form_area = $('#cb-form-area'),
        step_progress = $('#progressbar'),
        step_progress_length = step_progress.find('li').length - 1,
        last_step_progress = step_progress.find('li.active').eq(-1).index(),
        submitAjax = true;

    /**
     * Classbyte form steps
     */
    $(document).on('submit', '#cb_forms-only-ajax', function (e) {
        var $form = $(this);

        e.preventDefault();

        if ($form.prop('name') == "cb_payment_form" && $form.find('input[name=stripeToken]').length < 1) {
            submitAjax = false;

            Stripe.setPublishableKey('pk_test_yDqOpZs98ool13VgFRAil8DB');

            var stripeResponseHandler = function(status, response) {
                if (response.error) {
                    cb_form_area.find('.alert').remove();
                    cb_form_area.prepend('<div class="alert alert-danger">' + response.error.message + '</div>');
                    $form.find('button').prop('disabled', false);
                } else {
                    var token = response.id;
                    $form.append($('<input type="hidden" name="stripeToken" />').val(token));
                    submitAjax = true;
                    $form.submit();
                }
            };

            $form.find('button').prop('disabled', true);

            Stripe.card.createToken($form, stripeResponseHandler);
        }

        if (submitAjax == false) {
            return;
        }

        $.ajax({
            type: 'POST',
            url: cbConfig.ajax_url,
            async: false,
            data: {
                action: 'cb_form',
                form_name: $(this).prop('name'),
                form_data: $(this).serializeArray()
            },
            beforeSend: function () {
                cb_form_area.append('<div id="cb-form-loading"></div>');
                cb_form_area.find('.alert').remove();
                cb_form_area.find('.has-error').removeClass('has-error');
                $form.find('button').prop('disabled', true);
            },
            success: function(result) {
                try {
                    if (result.success == true) {
                        if (result.data.redirect) {
                            var delay = 4000;

                            if (result.data.object
                                && result.data.object.hasOwnProperty('session_id')
                                && result.data.object.session_id
                            ) {
                                $.removeCookie(cbConfig.CB_COOKIE_NAME, { path: cbConfig.COOKIEPATH });
                                $.cookie(cbConfig.CB_COOKIE_NAME, result.data.object.session_id, { path: cbConfig.COOKIEPATH });
                            }

                            $('#cb_forms-only-ajax').slideUp('fast', function() {
                                if (result.data.message) {
                                    cb_form_area.append('<div class="alert alert-success">' + result.data.message + ' Please wait while you\'re being redirecting...</div>');
                                }

                                if (result.data.hasOwnProperty('noDelay')
                                    && result.data.noDelay == true
                                ) {
                                    delay = 0;
                                }

                                setTimeout(function () {
                                    location.replace(result.data.redirect);
                                }, delay);
                            });
                        }

                        if (result.data !== "") {
                            if (last_step_progress <= step_progress_length) {
                                step_progress.find('li').eq(last_step_progress + 1).addClass('active');
                            } else {
                                last_step_progress = step_progress_length;
                            }

                            cb_form_area.empty().append(result.data);
                        }
                    } else if (result.success == false) {
                        if (result.data !== "") {
                            var error_data = result.data;
                            var display_errors = '<div class="alert alert-danger">';
                            if (error_data.message) {
                                display_errors += '<p>' + error_data.message + '</p>'
                            } else {
                                var labels = Object.keys(error_data);

                                $("#" + labels.join(', #')).each(function () {
                                    if (error_data[$(this).prop('id')]) {
                                        display_errors += '<p>' + $('label[for="' + $(this).prop('id') + '"], label[data-for="' + $(this).prop('id') + '"]').text().replace(' *', '');
                                        display_errors += ' ' + error_data[$(this).prop('id')];
                                        display_errors += '</p>';

                                        $(this).closest('.form-group').addClass('has-error');
                                    }
                                });
                            }
                            display_errors += '</div>';
                            cb_form_area.prepend(display_errors);
                        }
                    }
                } catch(e) {
                    console.log(e, result);
                }
            },
            complete: function () {
                $form.find('button').prop('disabled', false);
                $('#cb-form-loading').remove();
            }
        });
    });

    /**
     * Switch Login/Register form
     */
    $(document).on('click', 'a[data-switch-form]', function(e) {
        e.preventDefault();

        var login_form = $('form[name=cb_login_form]'),
            reg_form = $('form[name=cb_reg_form]');

        $('.alert').remove();

        if (!login_form.is(':visible')) {
            login_form.show();
            reg_form.hide();
        } else {
            login_form.hide();
            reg_form.show();
        }
    });

    $(document).on('click', '.mini-request', function(e) {
        e.preventDefault();

        var self = $(this), event = null, removeCookie = false;

        switch (self.prop('id')) {
            case 'cb_sign_out':
                event = 'sign_out';
                removeCookie = true;
                break;
            default:
                break;
        }

        $.ajax({
            type: 'POST',
            url: cbConfig.ajax_url,
            data: {
                action: 'mini_requests',
                event: event,
                _: Date.now()
            },
            async: false,
            beforeSend: function() {
                self.after('<img src="' + cbConfig.assets_url + 'img/progress-dots.gif" alt="" class="progress-loader">');
            },
            success: function(data) {
                if (data.success == true) {

                    // remove cookie session cookie
                    if (removeCookie) {
                        $.removeCookie(cbConfig.CB_COOKIE_NAME, { path: cbConfig.COOKIEPATH });
                    }

                    location.replace(data.data);
                }
            },
            complete: function () {
                self.next('.progress-loader').remove();
            }
        });
    });

    // Checking for possible already bootstrap css (improve if you can https://gist.github.com/rahilwazir/6d745cd89f514401eda9)
    /*
    $('link').each(function (index, element){
        var bootExist = /bootstrap\.(?:min\.)?css/.test($(element).prop('href'));

         if (bootExist) {
            // $('link#bootstrap-css-css').remove();
             return false;
         }
    });
    */

    return r;
}(jQuery));