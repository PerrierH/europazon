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
 * @category    Mageplaza
 * @package     Mageplaza_Labels
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ProductLabels\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class RoundingMethod
 * @package Mageplaza\ProductLabels\Model\Config\Source
 */
class RoundingMethod implements ArrayInterface
{
    const NORMAL        = 'normal';
    const ROUNDING_UP   = 'up';
    const ROUNDING_DOWN = 'down';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->toArray() as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $options;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            self::NORMAL        => __('Normal'),
            self::ROUNDING_UP   => __('Rounding Up'),
            self::ROUNDING_DOWN => __('Rounding Down')
        ];
    }
}
