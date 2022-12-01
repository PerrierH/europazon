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

use Magento\CatalogRule\Model\RuleFactory;
use Magento\Framework\Option\ArrayInterface;

/**
 * Class CatalogRule
 *
 * @package Mageplaza\CountdownTimer\Model\Config\Source
 */
class CatalogRule implements ArrayInterface
{
    /**
     * @var RuleFactory
     */
    protected $catalogRuleF;

    /**
     * CatalogRule constructor.
     *
     * @param RuleFactory $ruleFactory
     */
    public function __construct(RuleFactory $ruleFactory)
    {
        $this->catalogRuleF = $ruleFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $ruleCollection = $this->catalogRuleF->create()->getCollection();
        $options = [];
        foreach ($ruleCollection as $rule) {
            $options[] = ['value' => $rule->getId(), 'label' => $rule->getName()];
        }

        return $options;
    }
}
