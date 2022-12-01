/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category  Mageplaza
 * @package   Mageplaza_CountdownTimer
 * @copyright Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license   https://www.mageplaza.com/LICENSE.txt
 */
define(
    [
        'jquery',
        'underscore',
        'Mageplaza_CountdownTimer/js/product/mpcountdownInterval'
    ], function ($, _, mpcountdownInterval) {
        "use strict";
        $.widget(
            'mageplaza.mpcountdowntimer_configurable',
            {
                _create: function () {
                    var self      = this,
                        container = $('#product-options-wrapper');

                    /**
                     * show countdown timer with rule type is Inherit Conditions from Catalog Rules
                     */
                    if (self.options.ruleType === 2) {
                        $(document).on('swatch.initialized', function() {
                            var countdown;
                            var options = _.object(self.options.optionMap, []);
                            var element = $('.mp-countdown-timer');

                            $('.swatch-attribute').each(
                                function () {
                                    var attributeId = $(this).attr('data-attribute-id');

                                    options[attributeId] = $(this).find('.swatch-attribute-options .swatch-option:first').attr('data-option-id');
                                }
                            );

                            countdown = _.findWhere(self.options.configurable, options) || {};
                            if (countdown.countdown) {
                                element.html(countdown.countdown);
                                mpcountdownInterval.setCountdown(
                                    element.children(),
                                    countdown.rule,
                                    countdown.rule.timezone
                                );
                                self.setCountdownCss(element, countdown.style);
                            } else {
                                element.html('');
                            }
                        });
                    }


                    /**
                     * show countdown timer on changing configurable swatch options
                     */
                    container.on(
                        'click',
                        '.swatch-option',
                        function () {
                            var countdown;
                            var options = _.object(self.options.optionMap, []);
                            var element = $('.mp-countdown-timer');

                            $(this).parents('.swatch-opt').find('.swatch-attribute[data-option-selected]').each(
                                function () {
                                    var attributeId = $(this).attr('data-attribute-id');

                                    options[attributeId] = $(this).attr('data-option-selected');
                                }
                            );

                            countdown = _.findWhere(self.options.configurable, options) || {};
                            if (countdown.countdown) {
                                element.html(countdown.countdown);
                                mpcountdownInterval.setCountdown(
                                    element.children(),
                                    countdown.rule,
                                    countdown.rule.timezone
                                );
                                self.setCountdownCss(element, countdown.style);
                            } else {
                                element.html('');
                            }
                        }
                    );

                    /**
                     * show countdown timer on changing configurable dropdown options
                     */
                    container.on(
                        'change',
                        'select.super-attribute-select',
                        function () {
                            var countdown;
                            var options = _.object(self.options.optionMap, []);
                            var element = $('.mp-countdown-timer');

                            container.find('.super-attribute-select').each(
                                function () {
                                    var attributeId = this.config.id;

                                    options[attributeId] = this.value;
                                }
                            );

                            countdown = _.findWhere(self.options.configurable, options) || {};
                            if (countdown.countdown) {
                                element.html(countdown.countdown);
                                mpcountdownInterval.setCountdown(
                                    element.children(),
                                    countdown.rule,
                                    countdown.rule.timezone
                                );
                                self.setCountdownCss(element, countdown.style);
                            } else {
                                element.html('');
                            }
                        }
                    );
                },

                setCountdownCss: function (countDownTimer, style) {
                    countDownTimer.find('.mp-countdown-title').css("color", style.title_color);
                    countDownTimer.find('.mp-countdown-message').css("color", style.message_color);
                    countDownTimer.find('.mp-countdown-txt').css("color", style.text_color);
                    countDownTimer.find('.mp-countdown-days').css("color", style.number_color);
                    countDownTimer.find('.mp-countdown-hours').css("color", style.number_color);
                    countDownTimer.find('.mp-countdown-minutes').css("color", style.number_color);
                    countDownTimer.find('.mp-countdown-seconds').css("color", style.number_color);
                    if (countDownTimer.has('.style2').length
                        || countDownTimer.has('.style3').length
                        || countDownTimer.has('.style4').length
                    ) {
                        countDownTimer.find('.mp-countdown-clock').css({
                            "background-color": style.clock_color,
                            "border-color": style.clock_color
                        });
                    }
                    if (countDownTimer.has('.style5')) {
                        countDownTimer.find('.countdown-style5').css({
                            "background": style.clock_color,
                            "border-color": style.clock_color
                        });
                    }
                }
            }
        );

        return $.mageplaza.mpcountdowntimer_configurable;
    }
);
