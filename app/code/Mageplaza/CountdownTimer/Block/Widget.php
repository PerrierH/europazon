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

namespace Mageplaza\CountdownTimer\Block;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Mageplaza\CountdownTimer\Helper\Data;
use Mageplaza\CountdownTimer\Model\Config\Source\RuleType;
use Mageplaza\CountdownTimer\Model\ResourceModel\Rules as ResourceModelRules;
use Mageplaza\CountdownTimer\Model\Rules;
use Mageplaza\CountdownTimer\Model\RulesFactory;

/**
 * Class Widget
 *
 * @package Mageplaza\CountdownTimer\Block
 */
class Widget extends Template
{
    /** @var string */
    protected $_template = 'Mageplaza_CountdownTimer::product/timer.phtml';

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var RulesFactory
     */
    protected $ruleFactory;

    /**
     * @var ResourceModelRules
     */
    protected $resourceModel;

    /**
     * Widget constructor.
     *
     * @param Template\Context $context
     * @param Data $helperData
     * @param Registry $registry
     * @param RulesFactory $ruleFactory
     * @param ResourceModelRules $resourceModel
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Data $helperData,
        Registry $registry,
        RulesFactory $ruleFactory,
        ResourceModelRules $resourceModel,
        array $data = []
    ) {
        $this->helperData = $helperData;
        $this->registry = $registry;
        $this->ruleFactory = $ruleFactory;
        $this->resourceModel = $resourceModel;

        parent::__construct($context, $data);
    }

    /**
     * @return Rules|null
     */
    public function getDateByProduct()
    {
        $product = $this->getProduct();
        $ruleId = $this->getData('rule_id');
        if (!$ruleId || !$this->helperData->isEnabled()) {
            return null;
        }

        /** @var Rules $rule */
        $rule = $this->ruleFactory->create();
        $this->resourceModel->load($rule, $ruleId);
        if ($rule === null
            || !$rule->getStatus()
            || ($rule->getRuleType() !== RuleType::NONE_PRODUCT && $product === null)
            || !in_array($this->helperData->getCustomerGroup(), explode(',', $rule->getCustomerGroupIds()), true)
        ) {
            return null;
        }

        if ($rule->getRuleType() !== RuleType::NONE_PRODUCT) {
            $datetimeRule = $this->helperData->getDateTimeRule($product, $rule);
        } else {
            $datetimeRule = [
                'from_date' => $rule->getFromDate(),
                'to_date' => $rule->getToDate(),
                'save_amount' => '',
                'save_percent' => ''
            ];
        }

        return $this->helperData->processDateTime($datetimeRule, $rule);
    }

    /**
     * @return mixed|string
     */
    public function getStyleTimer()
    {
        $rule = $this->getDateByProduct();
        if ($rule !== null) {
            return $this->helperData->getStyleTimer($rule);
        }

        return '';
    }

    /**
     * @return string
     */
    public function getCountdownTemplate()
    {
        $rule = $this->getDateByProduct();
        if ($rule !== null) {
            return $this->helperData->getCountdownTemplate($rule, $this->getRequest()->getFullActionName());
        }

        return '';
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getProduct()
    {
        if (!$this->hasData('product')) {
            $this->setData('product', $this->registry->registry('product'));
        }

        $product = $this->getData('product');
        if ($product !== null && $product->getTypeInstance()->getStoreFilter($product) === null) {
            $product->getTypeInstance()->setStoreFilter($this->_storeManager->getStore(), $product);
        }

        return $product;
    }
}
