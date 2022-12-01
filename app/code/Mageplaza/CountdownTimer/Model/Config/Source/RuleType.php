<?php
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

namespace Mageplaza\CountdownTimer\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class RuleType
 *
 * @package Mageplaza\CountdownTimer\Model\Config\Source
 */
class RuleType implements ArrayInterface
{
    const ALL_PRODUCT_SPECIAL_PRICE = '0';
    const SPECIFIC_PRODUCT_SPECIAL_PRICE = '1';
    const INHERIT_CATALOG_RULE = '2';
    const NONE_PRODUCT = '3';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];

        foreach ($this->toArray() as $value => $label) {
            $options[] = compact('value', 'label');
        }

        return $options;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            self::ALL_PRODUCT_SPECIAL_PRICE => __('All Products with Special Prices'),
            self::SPECIFIC_PRODUCT_SPECIAL_PRICE => __('Specific Products with Special Prices'),
            self::INHERIT_CATALOG_RULE => __('Inherit Conditions from Catalog Rules'),
            self::NONE_PRODUCT => __('None Product Base'),
        ];
    }
}
