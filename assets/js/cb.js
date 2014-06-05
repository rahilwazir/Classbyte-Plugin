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
        last_step_progress = step_progress.find('li.active').eq(-1).index();

    /**
     * Classbyte form steps
     */
    $(document).on('submit', '#cb_forms-only-ajax', function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: cbConfig.ajax_url,
            data: {
                action: 'cb_form',
                form_name: $(this).prop('name'),
                form_data: $(this).serializeArray()
            },
            beforeSend: function () {
                cb_form_area.append('<div id="cb-form-loading"></div>');
                cb_form_area.find('.alert').remove();
            },
            success: function(result) {
                try {
                    if (result.success == true) {
                        switch (result.data.action) {
                            case 1:
                                location.replace('?action=payment');
                                break;
                            case 2:
                                // registration
                                break;
                            default:
                                break;
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
                            if (typeof error_data === "string") {
                                display_errors += '<p>' + error_data + '</p>'
                            } else {
                                var labels = Object.keys(error_data);

                                $("#" + labels.join(', #')).each(function () {
                                    if (error_data[$(this).prop('id')]) {
                                        display_errors += '<p><strong>' + $('label[for="' + $(this).prop('id') + '"]').text().replace(' *', '');
                                        display_errors += ' ' + error_data[$(this).prop('id')];
                                        display_errors += '</strong></p>';
                                    }
                                });
                            }
                            display_errors += '</div>';
                            cb_form_area.prepend(display_errors);
                        }
                    }
                } catch(e) {
                    console.log(result);
                }
            },
            complete: function () {
                $('#cb-form-loading').remove();
            }
        });
    })

    /**
     * Switch Login/Register form
     */
    $(document).on('click', 'a[data-switch-form]', function(e) {
        e.preventDefault();

        $.ajax({
            url: cbConfig.ajax_url,
            type: 'POST',
            data: {
                action: 'cb_switch_forms',
                form_of: $(this).data('switch-form') || ''
            },
            beforeSend: function() {
                cb_form_area.append('<div id="cb-form-loading"></div>');
            },
            success: function(data) {
                try {
                    if (data.success == true) {
                        if (data.data !== "") {
                            cb_form_area.empty().append(data.data);
                        }
                    }
                } catch(e) {
                    console.log(data);
                }
            },
            complete: function () {
                $('#cb-form-loading').remove();
            }
        });
    });

    return r;
}(jQuery));