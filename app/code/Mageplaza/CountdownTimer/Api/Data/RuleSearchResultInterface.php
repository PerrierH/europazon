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

namespace Mageplaza\CountdownTimer\Api\Data;

/**
 * Interface RuleSearchResultInterface
 * @package Mageplaza\CountdownTimer\Api\Data
 */
interface RuleSearchResultInterface
{
    /**
     * @return \Mageplaza\CountdownTimer\Api\Data\RuleInterface[]
     */
    public function getItems();

    /**
     * @param \Mageplaza\CountdownTimer\Api\Data\RuleInterface[] $items
     *
     * @return $this
     */
    public function setItems(array $items = null);
}
