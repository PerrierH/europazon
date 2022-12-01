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
use Mageplaza\CountdownTimer\Model\ResourceModel\Rules\Collection;
use Mageplaza\CountdownTimer\Model\RulesFactory;

/**
 * Class Rules
 *
 * @package Mageplaza\CountdownTimer\Model\Config\Source
 */
class Rules implements ArrayInterface
{
    /**
     * @var RulesFactory
     */
    protected $ruleFactory;

    /**
     * Rules constructor.
     *
     * @param RulesFactory $ruleFactory
     */
    public function __construct(RulesFactory $ruleFactory)
    {
        $this->ruleFactory = $ruleFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        /** @var Collection $ruleCollection */
        $ruleCollection = $this->ruleFactory->create()->getCollection();
        $ruleCollection->addFieldToFilter('status', true);

        return $ruleCollection->toOptionArray();
    }
}
