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
 * Class ClockStyle
 *
 * @package Mageplaza\CountdownTimer\Model\Config\Source
 */
class ClockStyle implements ArrayInterface
{
    const STYLE_1 = 'style1';
    const STYLE_2 = 'style2';
    const STYLE_3 = 'style3';
    const STYLE_4 = 'style4';
    const STYLE_5 = 'style5';

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
            self::STYLE_1 => __('Simple'),
            self::STYLE_2 => __('Circle'),
            self::STYLE_3 => __('Square'),
            self::STYLE_4 => __('Stack'),
            self::STYLE_5 => __('Modern')
        ];
    }
}
