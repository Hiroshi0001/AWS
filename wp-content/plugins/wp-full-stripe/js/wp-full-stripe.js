/*
 Plugin Name: WP Full Stripe
 Plugin URI: https://paymentsplugin.com
 Description: Complete Stripe payments integration for Wordpress
 Author: Mammothology
 Version: 3.9.1
 Author URI: https://paymentsplugin.com
 */

Stripe.setPublishableKey(stripekey);

jQuery.noConflict();
(function ($) {
    $(function () {
        var FORM_TYPE_PAYMENT = 'payment';
        var FORM_STYLE_DEFAULT = 'default';
        var FORM_STYLE_COMPACT = 'compact';

        function logError(handlerName, jqXHR, textStatus, errorThrown) {
            if (window.console) {
                console.log(handlerName + '.error(): textStatus=' + textStatus);
                console.log(handlerName + '.error(): errorThrown=' + errorThrown);
                if (jqXHR) {
                    console.log(handlerName + '.error(): jqXHR.status=' + jqXHR.status);
                    console.log(handlerName + '.error(): jqXHR.responseText=' + jqXHR.responseText);
                }
            }
        }

        function logException(source, response) {
            if (window.console && response) {
                if (response.ex_msg) {
                    console.log('ERROR: source=' + source + ', message=' + response.ex_msg);
                }
            }
        }

        function showErrorMessage(message, formId, afterSelector) {
            var errorPanel;
            if (typeof afterSelector == "undefined") {
                errorPanel = __getMessagePanelFor(formId, null);
            } else {
                errorPanel = __getMessagePanelFor(formId, afterSelector);
            }
            errorPanel.addClass('alert alert-error').html(message);
            __scrollToMessagePanel(formId);
        }

        function showInfoMessage(message, formId, afterSelector) {
            var infoPanel;
            if (typeof afterSelector == "undefined") {
                infoPanel = __getMessagePanelFor(formId, null);
            } else {
                infoPanel = __getMessagePanelFor(formId, afterSelector);
            }
            infoPanel.addClass('alert alert-success').html(message);
            __scrollToMessagePanel(formId);
        }

        function clearMessagePanel(formId, afterSelector) {
            var panel = __getMessagePanelFor(formId, afterSelector);
            panel.removeClass('alert alert-error alert-success');
            panel.html("");
        }

        function __getMessagePanelFor(formId, afterSelector) {
            var panel = $('.payment-errors__' + formId);
            if (panel.length == 0) {
                if (afterSelector == null) {
                    panel = $('<p>', {class: 'payment-errors__' + formId}).prependTo('form[data-form-id=' + formId + ']');
                } else {
                    panel = $('<p>', {class: 'payment-errors__' + formId}).insertAfter(afterSelector);
                }
            }
            return panel;
        }

        function __scrollToMessagePanel(formId) {
            var panel = $('.payment-errors__' + formId);
            if (panel && panel.offset() && panel.offset().top) {
                if (!__isInViewport(panel)) {
                    $('html, body').animate({
                        scrollTop: panel.offset().top - 100
                    }, 1000);
                }
            }
            if (panel) {
                panel.fadeIn(500).fadeOut(500).fadeIn(500);
            }
        }

        function __isInViewport($elem) {
            var $window = $(window);

            var docViewTop = $window.scrollTop();
            var docViewBottom = docViewTop + $window.height();

            var elemTop = $elem.offset().top;
            var elemBottom = elemTop + $elem.height();

            return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
        }

        function updateButtonTitle(formId, buttonTitle, currencySymbol, amount) {
            var buttonTitleParams = [];
            buttonTitleParams.push(buttonTitle);
            buttonTitleParams.push(currencySymbol);
            buttonTitleParams.push(amount);
            $('#payment-form-submit__' + formId).html(vsprintf("%s %s%0.2f", buttonTitleParams));
        }

        function createResponseHandlerByFormId(formId, formType, formStyle) {
            return function (status, response) {

                var afterSelector = null;
                if (FORM_STYLE_DEFAULT == formStyle) {
                    afterSelector = '#legend__' + formId;
                }

                var $form = $('form[data-form-id=' + formId + ']');

                if (response.error) {

                    var code = response.error.code;
                    var param = response.error.param;
                    // Show the errors
                    if (code) {
                        if (code == 'invalid_number' && param != '' && wpfs_L10n.hasOwnProperty(code + '_' + param)) {
                            showErrorMessage(wpfs_L10n[code + '_' + param], formId, afterSelector);
                        } else if (wpfs_L10n.hasOwnProperty(code)) {
                            showErrorMessage(wpfs_L10n[code], formId, afterSelector);
                        }
                    } else {
                        showErrorMessage(response.error.message, formId, afterSelector);
                    }
                    $form.find('button').prop('disabled', false);
                    $('#show-loading__' + formId).hide();
                } else {
                    // token contains id, last4, and card type
                    var token = response.id;
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "' />");

                    //post payment via ajax
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: $form.serialize(),
                        cache: false,
                        dataType: "json",
                        success: function (data) {
                            if (data.success) {
                                //clear form fields
                                $form.find('input:text, input:password').val('');
                                $('#fullstripe-custom-amount__' + formId).prop('selectedIndex', 0);
                                $('#fullstripe-plan__' + formId).prop('selectedIndex', 0);
                                $('#fullstripe-address-country__' + formId).prop('selectedIndex', 0);
                                //inform user of success
                                showInfoMessage(data.msg, formId);
                                $form.find('button').prop('disabled', false);
                                if (data.redirect) {
                                    setTimeout(function () {
                                        window.location = data.redirectURL;
                                    }, 1500);
                                }
                            } else {
                                // show the errors on the form
                                showErrorMessage(data.msg, formId);
                                logException('Stripe form ' + formId, data);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            showErrorMessage(wpfs_L10n.internal_error, formId);
                        },
                        complete: function () {
                            $form.find('button').prop('disabled', false);
                            $("#show-loading__" + formId).hide();
                        }
                    });
                }
            };
        }

        $(".loading-animation").hide();

        $('.fullstripe-custom-amount').change(function () {
            var formId = $(this).data('form-id');
            var showAmount = $(this).data('show-amount');
            var buttonTitle = $(this).data('button-title');
            var currencySymbol = $(this).data('currency-symbol');

            var val = $(this).val();
            if (val == 'other') {
                $('#fullstripe-list-of-amounts-custom-amount__' + formId).show().focus();
            } else {
                $('#fullstripe-list-of-amounts-custom-amount__' + formId).hide();
                var amount = parseFloat(val);
                if (showAmount == '1' && !isNaN(amount)) {
                    updateButtonTitle(formId, buttonTitle, currencySymbol, amount);
                }
            }
        });

        $('.fullstripe-list-of-amounts-custom-amount').change(function () {
            var formId = $(this).data('form-id');
            if ($('#fullstripe-list-of-amounts-custom-amount__' + formId).is(':visible')) {
                var showAmount = $(this).data('show-amount');
                var buttonTitle = $(this).data('button-title');
                var currencySymbol = $(this).data('currency-symbol');
                var amount = parseFloat($(this).val());
                if (showAmount == '1' && !isNaN(amount)) {
                    updateButtonTitle(formId, buttonTitle, currencySymbol, amount);
                }
            }
        });

        $('.payment-form').submit(function (e) {
            var formId = $(this).data('form-id');

            clearMessagePanel(formId, '#legend__' + formId);
            $("#show-loading__" + formId).show();

            var $form = $(this);

            var amountIndex = $form.find('select[name=fullstripe_custom_amount] option:selected').data('amount-index');
            $form.append($('<input type="hidden" name="fullstripe_amount_index">').val(amountIndex));

            // Disable the submit button
            $form.find('button').prop('disabled', true);

            var responseHandler = createResponseHandlerByFormId(formId, FORM_TYPE_PAYMENT, FORM_STYLE_DEFAULT);
            Stripe.createToken($form, responseHandler);
            return false;
        });

        $('.payment-form-compact').submit(function (e) {
            var formId = $(this).data('form-id');

            clearMessagePanel(formId);
            $("#show-loading__" + formId).show();

            var $form = $(this);

            var amountIndex = $form.find('select[name=fullstripe_custom_amount] option:selected').data('amount-index');
            $form.append($('<input type="hidden" name="fullstripe_amount_index">').val(amountIndex));

            // Disable the submit button
            $form.find('button').prop('disabled', true);

            var responseHandler = createResponseHandlerByFormId(formId, FORM_TYPE_PAYMENT, FORM_STYLE_COMPACT);
            Stripe.createToken($form, responseHandler);
            return false;
        });

        var coupon = false;
        $('.fullstripe-plan').change(function () {
            var formId = $(this).data('form-id');
            var plan = $("#fullstripe-plan__" + formId).val();
            var planSelector = "option[value='" + plan + "']";
            var setupFee = parseInt($("#fullstripe-plan__" + formId + ' option:selected').data('setup-fee'));
            var option = $("#fullstripe-plan__" + formId).find($('<div/>').html(planSelector).text());
            var interval = option.attr('data-interval');
            var intervalCount = parseInt(option.attr("data-interval-count"));
            var amount = parseFloat(option.attr('data-amount') / 100);
            var currencySymbol = option.attr("data-currency");

            var planDetailsPattern = wpfs_L10n.plan_details_with_singular_interval;
            var planDetailsParams = [];
            planDetailsParams.push(currencySymbol);
            planDetailsParams.push(amount);

            if (intervalCount > 1) {
                planDetailsPattern = wpfs_L10n.plan_details_with_plural_interval;
                planDetailsParams.push(intervalCount);
                planDetailsParams.push(interval);
            } else {
                planDetailsParams.push(interval);
            }

            if (coupon != false) {
                planDetailsPattern = intervalCount > 1 ? wpfs_L10n.plan_details_with_plural_interval_with_coupon : wpfs_L10n.plan_details_with_singular_interval_with_coupon;
                var total;
                if (coupon.percent_off != null) {
                    total = amount * (1 - ( parseInt(coupon.percent_off) / 100 ));
                } else {
                    total = amount - parseFloat(coupon.amount_off) / 100;
                }
                total = total.toFixed(2);
                planDetailsParams.push(total);
                $(this).parents('form:first').append($('<input type="hidden" name="amount_with_coupon_applied">').val(total * 100));
            }

            if (setupFee > 0) {
                planDetailsPattern = intervalCount > 1 ? (coupon != false ? wpfs_L10n.plan_details_with_plural_interval_with_coupon_with_setupfee : wpfs_L10n.plan_details_with_plural_interval_with_setupfee) : (coupon != false ? wpfs_L10n.plan_details_with_singular_interval_with_coupon_with_setupfee : wpfs_L10n.plan_details_with_singular_interval_with_setupfee);
                var sf = (setupFee / 100).toFixed(2);
                planDetailsParams.push(currencySymbol);
                planDetailsParams.push(sf);
            }

            var planDetailsMessage = vsprintf(planDetailsPattern, planDetailsParams);
            var planDetails = $('#fullstripe-plan-details__' + formId);
            if (planDetails.length == 0) {
                planDetails = $('<p>', {id: 'fullstripe-plan-details__' + formId}).appendTo($('#payment-form-submit__' + formId).parent());
            }
            planDetails.text(planDetailsMessage);

        }).change();

        $('.payment-form-coupon').click(function (e) {
            var formId = $(this).data('form-id');
            // tnagy coupons are only used by subscription forms where we do not use an /afterSelector/
            var afterSelector = null;
            e.preventDefault();
            var cc = $('#fullstripe-coupon-input__' + formId).val();
            if (cc.length > 0) {
                $(this).prop('disabled', true);
                clearMessagePanel(formId);
                $('#show-loading-coupon__' + formId).show();

                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {action: 'wp_full_stripe_check_coupon', code: cc},
                    cache: false,
                    dataType: "json",
                    success: function (data) {
                        if (data.valid) {
                            coupon = data.coupon;
                            $('#fullstripe-plan__' + formId).change();
                            showInfoMessage(data.msg, formId, afterSelector);
                        } else {
                            showErrorMessage(data.msg, formId, afterSelector);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        showErrorMessage(wpfs_L10n.internal_error, formId, afterSelector);
                    },
                    complete: function () {
                        $('#fullstripe-check-coupon-code__' + formId).prop('disabled', false);
                        $('#show-loading-coupon__' + formId).hide();
                    }
                });
            }
            return false;
        });
    });
})(jQuery);
