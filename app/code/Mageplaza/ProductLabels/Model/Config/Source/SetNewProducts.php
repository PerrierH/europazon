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
 * Class SetNewProducts
 * @package Mageplaza\ProductLabels\Model\Config\Source
 */
class SetNewProducts implements ArrayInterface
{
    const CREATED_DATE      = 'created_date';
    const FROM_DATE_TO_DATE = 'from_date_and_to_date';

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
            self::CREATED_DATE      => __('Create Date'),
            self::FROM_DATE_TO_DATE => __('New from Date and New to Date')
        ];
    }
}
