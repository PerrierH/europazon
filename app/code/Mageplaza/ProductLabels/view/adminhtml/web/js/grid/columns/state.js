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
 * @package     Mageplaza_ProductLabels
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

define([
    'Magento_Ui/js/grid/columns/select',
    'mage/translate'
], function (Column, $t) {
    'use strict';

    return Column.extend({
        defaults: {
            bodyTmpl: 'ui/grid/cells/html'
        },
        getLabel: function (record) {
            var label = this._super(record);

            switch (record.state){
                case "running":
                    label = '<span class="grid-severity-notice"><span>' + $t("Running") + '</span></span>';
                    break;
                case "queue":
                    label = '<span class="grid-severity-notice"><span>' + $t("Queue") + '</span></span>';
                    break;
                case "done":
                    label = '<span class="grid-severity-minor"><span>' + $t("Done") + '</span></span>';
                    break;
            }
            return label;
        }
    });
});