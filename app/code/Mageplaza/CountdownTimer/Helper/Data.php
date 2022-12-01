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

namespace Mageplaza\CountdownTimer\Helper;

use DateTime;
use DateTimeZone;
use Exception;
use Magento\Catalog\Model\Product;
use Magento\CatalogRule\Model\CatalogRuleRepository;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\SalesRule\Model\Rule;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\Core\Helper\AbstractData;
use Mageplaza\CountdownTimer\Model\Config\Source\ClockStyle;
use Mageplaza\CountdownTimer\Model\Config\Source\RuleType;
use Mageplaza\CountdownTimer\Model\ResourceModel\Rules as ResourceModelRules;
use Mageplaza\CountdownTimer\Model\ResourceModel\Rules\Collection;
use Mageplaza\CountdownTimer\Model\ResourceModel\Rules\CollectionFactory;
use Mageplaza\CountdownTimer\Model\Rules;
use Mageplaza\CountdownTimer\Model\RulesFactory;

/**
 * Class Data
 *
 * @package Mageplaza\CountdownTimer\Helper
 */
class Data extends AbstractData
{
    const CONFIG_MODULE_PATH = 'mpcountdowntimer';

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var RulesFactory
     */
    protected $rulesFactory;

    /**
     * @var ResourceModelRules
     */
    protected $resourceModel;

    /**
     * @var CatalogRuleRepository
     */
    protected $ruleRepository;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var Configurable
     */
    protected $configurableType;

    /**
     * @var int|null
     */
    protected $customerGroupId;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     * @param CustomerSession $customerSession
     * @param CollectionFactory $collectionFactory
     * @param RulesFactory $rulesFactory
     * @param ResourceModelRules $resourceModel
     * @param CatalogRuleRepository $ruleRepository
     * @param PriceCurrencyInterface $priceCurrency
     * @param Configurable $configurable
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        CustomerSession $customerSession,
        CollectionFactory $collectionFactory,
        RulesFactory $rulesFactory,
        ResourceModelRules $resourceModel,
        CatalogRuleRepository $ruleRepository,
        PriceCurrencyInterface $priceCurrency,
        Configurable $configurable
    ) {
        $this->customerSession = $customerSession;
        $this->collectionFactory = $collectionFactory;
        $this->rulesFactory = $rulesFactory;
        $this->resourceModel = $resourceModel;
        $this->ruleRepository = $ruleRepository;
        $this->priceCurrency = $priceCurrency;
        $this->configurableType = $configurable;

        parent::__construct($context, $objectManager, $storeManager);
    }

    /**
     * @return string
     */
    public function getCustomerGroup()
    {
        if ($this->customerGroupId !== null) {
            return $this->customerGroupId;
        }

        return (string)$this->customerSession->getCustomerGroupId();
    }

    /**
     * @param int|string $value
     */
    public function setCustomerGroupId($value)
    {
        $this->customerGroupId = $value;
    }

    /**
     * @return string
     */
    public function getCurrentStore()
    {
        try {
            return (string)$this->storeManager->getStore()->getId();
        } catch (Exception $e) {
            $this->_logger->warning($e->getMessage());
        }

        return 0;
    }

    /**
     * @param string $page
     *
     * @return Collection
     */
    public function getActiveRules($page)
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();

        return $collection->addActiveFilter($this->getCustomerGroup(), $this->getCurrentStore())
            ->addFieldToFilter('rule_type', ['neq' => RuleType::NONE_PRODUCT])
            ->addPageTypeFilter($page)
            ->setOrder('rule_id', 'DESC');
    }

    /**
     * @param Product $product
     *
     * @return bool
     */
    public function hasSpecialPrice(Product $product)
    {
        return $product instanceof SaleableInterface
            && $product->getSpecialPrice()
            && $product->getSpecialFromDate();
    }

    /**
     * @param Product $product
     * @param string $page
     *
     * @return Rules|null
     */
    public function getRuleData(Product $product, $page)
    {
        $ruleId = '';
        $data = [];
        $rules = $this->getActiveRules($page);
        foreach ($rules as $rule) {
            $datetime = $this->getDateTimeRule($product, $rule);
            if (!empty($datetime)) {
                $ruleId = $rule->getId();
                $data[$ruleId] = $datetime;
            }
        }

        if (!empty($ruleId)) {
            /** @var Rules $ruleData */
            $ruleData = $this->rulesFactory->create();
            $this->resourceModel->load($ruleData, $ruleId);

            return $this->processDateTime($data[$ruleId], $ruleData);
        }

        return null;
    }

    /**
     * @param array $data
     * @param Rules $rule
     *
     * @return Rules|null
     */
    public function processDateTime(array $data, Rules $rule)
    {
        if (!array_key_exists('from_date', $data) || !array_key_exists('to_date', $data)) {
            return null;
        }

        if ($data['from_date'] === null && $data['to_date'] === null) {
            return null;
        }

        if ($rule->getRuleType() !== RuleType::NONE_PRODUCT) {
            $fromDate = date('Y/m/d', strtotime($data['from_date']));
            $toDate = ($data['to_date'] === null) ? null
                : date('Y/m/d', strtotime(+1 . ' day', strtotime($data['to_date'])));
        } else {
            $fromDate = date('Y/m/d H:i', strtotime($data['from_date']));
            $toDate = date('Y/m/d H:i', strtotime($data['to_date']));
        }
        $datetime = new DateTime('now');
        $datetime->setTimezone(new DateTimeZone($rule->getTimeZone()));
        $currentDateTime = $datetime->format('Y/m/d H:i:s');

        if ($toDate !== null && $toDate < $currentDateTime) {
            return null;
        }

        if ($toDate === null && $fromDate < $currentDateTime) {
            return null;
        }

        if (isset($data['save_amount_value']) ) {
            $saveAmountValue = $data['save_amount_value'];
        }else{
            $saveAmountValue = '';
        }

        $rule->setData('from_date', $fromDate);
        $rule->setData('to_date', $toDate);
        $rule->setData('save_amount', $data['save_amount']);
        $rule->setData('save_amount_value', $saveAmountValue);
        $rule->setData('save_percent', $data['save_percent']);
        $rule->setData('timezone', $rule->getTimeZone());
        $rule->setData('remaining_time', strtotime($data['to_date']) - $datetime->getTimestamp());

        return $rule;
    }

    /**
     * @param Product $product
     * @param Rules $rule
     *
     * @return array
     */
    public function getDateTimeRule(Product $product, Rules $rule)
    {
        switch ($rule->getRuleType()) {
            case RuleType::ALL_PRODUCT_SPECIAL_PRICE:
                $datetime = $this->checkSpecialProduct($product);
                break;
            case RuleType::SPECIFIC_PRODUCT_SPECIAL_PRICE:
                $datetime = $this->checkSpecificProduct($product, $rule);
                break;
            case RuleType::INHERIT_CATALOG_RULE:
                $datetime = $this->checkProductByCatalogRule($product, $rule->getCatalogRuleId());
                break;
            default:
                $datetime = [
                    'from_date' => $rule->getFromDate(),
                    'to_date' => $rule->getToDate(),
                    'save_amount' => '',
                    'save_percent' => ''
                ];
        }

        return $datetime;
    }

    /**
     * @param Product $product
     *
     * @return array
     */
    protected function checkSpecialProduct(Product $product)
    {
        if ($this->hasSpecialPrice($product)) {
            $fromDate = $product->getSpecialFromDate();
            $toDate = $product->getSpecialToDate();
            $priceAmount = (float)$product->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue();
            $specialPriceAmount = (float)$product->getPriceInfo()->getPrice('special_price')->getSpecialPrice();
            $finalPriceAmount = $specialPriceAmount ?:
                (float)$product->getPriceInfo()->getPrice('final_price')->getAmount()->getValue();

            $saveAmount = ($product->getTypeId() === 'bundle') ? ''
                : $priceAmount - $finalPriceAmount;
            $savePercent = ($product->getTypeId() === 'bundle') ? $product->getSpecialPrice()
                : 100 - round(($finalPriceAmount / $priceAmount) * 100);

            return [
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'save_amount' => $this->priceCurrency->convertAndFormat($saveAmount),
                'save_amount_value' => $saveAmount,
                'save_percent' => round($savePercent) . '%'
            ];
        }

        return [];
    }

    /**
     * @param Product $product
     * @param Rules $rules
     *
     * @return array
     */
    protected function checkSpecificProduct(Product $product, Rules $rules)
    {
        if ($rules->getApplyProduct($product)) {
            return $this->checkSpecialProduct($product);
        }

        return [];
    }

    /**
     * @param Product $product
     * @param string|int $ruleId
     *
     * @return array
     */
    protected function checkProductByCatalogRule(Product $product, $ruleId)
    {
        $data = [];
        try {
            /** @var \Magento\CatalogRule\Model\Rule $catalogRule */
            $catalogRule = $this->ruleRepository->get($ruleId);
            $configurableProducts = $this->configurableType->getParentIdsByChild($product->getId());

            /* validate configurable product with catalog rule */
            if ($configurableProducts && $catalogRule->getIsActive()) {
                foreach ($configurableProducts as $confProductId) {
                    if ($catalogRule->getConditions()->validateByEntityId($confProductId)) {
                        $data = $this->getCatalogRuleData($catalogRule);
                    }
                }
            } elseif ($catalogRule->getIsActive()
                && $catalogRule->getConditions()->validateByEntityId($product->getId())
            ) {
                $data = $this->getCatalogRuleData($catalogRule);
            }
        } catch (NoSuchEntityException $e) {
            $this->_logger->critical($e->getMessage());
        }

        return $data;
    }

    /**
     * @param \Magento\CatalogRule\Model\Rule $catalogRule
     *
     * @return array
     */
    protected function getCatalogRuleData($catalogRule)
    {
        $data = [];
        $ruleAction = $catalogRule->getSimpleAction();
        $data['from_date'] = $catalogRule->getFromDate();
        $data['to_date'] = $catalogRule->getToDate();
        $data['save_amount'] = in_array($ruleAction, ['by_fixed', 'cart_fixed', 'buy_x_get_y'])
            ? $this->priceCurrency->convertAndFormat($catalogRule->getDiscountAmount()) : '';
        $data['save_amount_value'] = in_array($ruleAction, ['by_fixed', 'cart_fixed', 'buy_x_get_y'])
            ? $catalogRule->getDiscountAmount() : '';
        $data['save_percent'] = ($ruleAction === Rule::BY_PERCENT_ACTION)
            ? (float)$catalogRule->getDiscountAmount() . '%' : '';

        return $data;
    }

    /**
     * @param Rules $rule
     *
     * @return mixed|string
     */
    public function getStyleTimer(Rules $rule)
    {
        $style = '';
        if ($rule->getEnableBeforeStart() && $rule->getFromDate() > date('Y/m/d')) {
            $style = str_replace(
                [
                    'title_color_before',
                    'message_color_before',
                    'clock_color_before',
                    'number_color_before',
                    'text_color_before'
                ],
                [
                    'title_color',
                    'message_color',
                    'clock_color',
                    'number_color',
                    'text_color'
                ],
                $rule->getCssBefore()
            );
        }

        if ($rule->getEnableWhileRunning() && $rule->getFromDate() <= date('Y/m/d')) {
            $style = str_replace(
                [
                    'title_color_running',
                    'message_color_running',
                    'clock_color_running',
                    'number_color_running',
                    'text_color_running'
                ],
                [
                    'title_color',
                    'message_color',
                    'clock_color',
                    'number_color',
                    'text_color'
                ],
                $rule->getCssRunning()
            );
        }

        return $style;
    }

    /**
     * @param Rules $rule
     * @param null $page
     *
     * @return string
     */
    public function getCountdownTemplate(Rules $rule, $page = null)
    {
        $template = '';
        if ($rule->getEnableBeforeStart() && $rule->getFromDate() > date('Y/m/d')) {
            $template = $page !== 'catalog_product_view' ? $this->processTemplate($rule)->getTemplateBeforeCategory()
                : $this->processTemplate($rule)->getTemplateBeforeProduct();
        }

        if ($rule->getEnableWhileRunning() && $rule->getFromDate() <= date('Y/m/d')) {
            $template = $page !== 'catalog_product_view' ? $this->processTemplate($rule)->getTemplateRunningCategory()
                : $this->processTemplate($rule)->getTemplateRunningProduct();
        }

        return $template;
    }

    /**
     * @param Rules $rule
     *
     * @return Rules
     */
    public function processTemplate(Rules $rule)
    {
        $templateBeforeProduct = $rule->getTemplateBeforeProduct();
        $templateBeforeProduct = str_replace(
            ['{{save_amount}}', '{{save_percent}}', '{{clock}}'],
            [
                $rule->getData('save_amount'),
                $rule->getData('save_percent'),
                $this->getClockTemplate($rule->getClockStyleBefore())
            ],
            $templateBeforeProduct
        );

        $templateBeforeCategory = $rule->getTemplateBeforeCategory();
        $templateBeforeCategory = str_replace(
            ['{{save_amount}}', '{{save_percent}}', '{{clock}}'],
            [
                $rule->getData('save_amount'),
                $rule->getData('save_percent'),
                $this->getClockTemplate($rule->getClockStyleBefore())
            ],
            $templateBeforeCategory
        );

        $templateRunningProduct = $rule->getTemplateRunningProduct();
        $templateRunningProduct = str_replace(
            ['{{save_amount}}', '{{save_percent}}', '{{clock}}'],
            [
                $rule->getData('save_amount'),
                $rule->getData('save_percent'),
                $this->getClockTemplate($rule->getClockStyleRunning())
            ],
            $templateRunningProduct
        );

        $templateRunningCategory = $rule->getTemplateRunningCategory();
        $templateRunningCategory = str_replace(
            ['{{save_amount}}', '{{save_percent}}', '{{clock}}'],
            [
                $rule->getData('save_amount'),
                $rule->getData('save_percent'),
                $this->getClockTemplate($rule->getClockStyleRunning())
            ],
            $templateRunningCategory
        );

        $rule->setTemplateBeforeProduct($templateBeforeProduct);
        $rule->setTemplateBeforeCategory($templateBeforeCategory);
        $rule->setTemplateRunningProduct($templateRunningProduct);
        $rule->setTemplateRunningCategory($templateRunningCategory);

        return $rule;
    }

    /**
     * @param string $style
     *
     * @return string
     */
    public function getClockTemplate($style)
    {
        $style5 = ($style === ClockStyle::STYLE_5) ? 'countdown-style5' : '';

        $template = '<div class="flex-box ' . $style5 . '">
    <div class="' . $style . ' mp-countdown-clock">
        <span class="' . $style . '-txt1 mp-countdown-days"></span>
        <span class="' . $style . '-txt2 mp-countdown-txt">' . __('Days') . '</span>
    </div>
    <div class="' . $style . ' mp-countdown-clock">
        <span class="' . $style . '-txt1 mp-countdown-hours"></span>
        <span class="' . $style . '-txt2 mp-countdown-txt">' . __('Hours') . '</span>
    </div>
    <div class="' . $style . ' mp-countdown-clock">
        <span class="' . $style . '-txt1 mp-countdown-minutes"></span>
        <span class="' . $style . '-txt2 mp-countdown-txt">' . __('Minutes') . '</span>
    </div>
    <div class="' . $style . ' mp-countdown-clock">
        <span class="' . $style . '-txt1 mp-countdown-seconds"></span>
        <span class="' . $style . '-txt2 mp-countdown-txt">' . __('Seconds') . '</span>
    </div>
</div>';

        $template1 = '<div class="style1-container">
    <div class="' . $style . ' mp-countdown-clock">
        <span class="' . $style . '-txt1 mp-countdown-days"></span>
        <span class="' . $style . '-txt2 mp-countdown-txt fs-12">' . __('Days') . '</span>
    </div>
    <div class="' . $style . ' mp-countdown-clock">
        <span class="' . $style . '-txt1 mp-countdown-hours"></span>
        <span class="' . $style . '-txt2 mp-countdown-txt fs-18">:</span>
    </div>
    <div class="' . $style . ' mp-countdown-clock">
        <span class="' . $style . '-txt1 mp-countdown-minutes"></span>
        <span class="' . $style . '-txt2 mp-countdown-txt fs-18">:</span>
    </div>
    <div class="' . $style . ' mp-countdown-clock">
        <span class="' . $style . '-txt1 mp-countdown-seconds"></span>
    </div>
</div>';

        return ($style === ClockStyle::STYLE_1) ? $template1 : $template;
    }

    /**
     * @param mixed $data
     *
     * @return string
     */
    public function getJsonEncode($data)
    {
        return self::jsonEncode($data);
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function getJsonDecode($data)
    {
        return self::jsonDecode($data);
    }

    /**
     * @return StoreManagerInterface
     */
    public function getStoreManager()
    {
        return $this->storeManager;
    }
}
