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
 * @category    Mageplaza
 * @package     Mageplaza_CustomForm
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

'use strict';
define([
    'underscore',
    'Magento_Ui/js/form/element/abstract',
    './dependency'
], function (_, Element, dependency) {

    return Element.extend(dependency).extend({
        defaults: {
            elementTmpl: 'Mageplaza_CustomForm/form/field/radio'
        },
        initObservable: function () {
            this._super();
            this.addFieldToProvider();
            this.dependencyObs();

            return this;
        },
        getOptionsByRow: function () {
            var countPerRow = +this.countPerRow || this.options.length;
            var options     = _.groupBy(this.options, function (element, index) {
                return Math.floor(index / countPerRow);
            });

            options         = _.toArray(options);
            return options;
        }
    });
});
