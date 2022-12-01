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
 * Class PageView
 *
 * @package Mageplaza\CountdownTimer\Model\Config\Source
 */
class PageView implements ArrayInterface
{
    const CATALOG_VIEW = '0';
    const PRODUCT_VIEW = '1';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::CATALOG_VIEW, 'label' => __('Category Page')],
            ['value' => self::PRODUCT_VIEW, 'label' => __('Product Page')]
        ];
    }
}
