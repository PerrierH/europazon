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

namespace Mageplaza\CountdownTimer\Model;

use Magento\Catalog\Model\Product;
use Magento\CatalogRule\Model\Rule\Condition\Combine;
use Magento\CatalogRule\Model\Rule\Condition\CombineFactory as ProductCombineFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Model\ResourceModel\Iterator;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Rule\Model\AbstractModel;
use Magento\Rule\Model\Action\Collection;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\CountdownTimer\Api\Data\RuleInterface;
use Mageplaza\CountdownTimer\Helper\Data;
use Mageplaza\CountdownTimer\Model\ResourceModel\Rules as ResourceModelRules;

/**
 * Countdown Timer model
 *
 * @package Mageplaza\CountdownTimer\Model
 *
 * @method string getCssBefore()
 * @method Rules setCssBefore(string $value)
 * @method string getCssRunning()
 * @method Rules setCssRunning(string $value)
 * @method Rules setUpdatedAt(string $value)
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Rules extends AbstractModel implements RuleInterface
{
    /**
     * Cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'mageplaza_countdown_timer_rules';

    /**
     * Cache tag
     *
     * @var string
     */
    protected $_cacheTag = 'mageplaza_countdown_timer_rules';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'mageplaza_countdown_timer_rules';

    /**
     * @var ProductCombineFactory
     */
    protected $_condProdCombineF;

    /**
     * @var Iterator
     */
    protected $resourceIterator;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * Rules constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param TimezoneInterface $localeDate
     * @param ProductCombineFactory $_condProdCombineF
     * @param StoreManagerInterface $storeManager
     * @param Data $helperData
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        TimezoneInterface $localeDate,
        ProductCombineFactory $_condProdCombineF,
        StoreManagerInterface $storeManager,
        Data $helperData,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_condProdCombineF = $_condProdCombineF;
        $this->storeManager = $storeManager;
        $this->helperData = $helperData;

        parent::__construct($context, $registry, $formFactory, $localeDate, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init(ResourceModelRules::class);
        $this->setIdFieldName('rule_id');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get rule condition combine model instance
     *
     * @return Combine|\Magento\SalesRule\Model\Rule\Condition\Combine
     */
    public function getConditionsInstance()
    {
        return $this->_condProdCombineF->create();
    }

    /**
     * Get condition product combine model instance
     *
     * @return Combine|Collection
     */
    public function getActionsInstance()
    {
        return $this->_condProdCombineF->create();
    }

    /**
     * @param string $formName
     *
     * @return string
     */
    public function getConditionsFieldSetId($formName = '')
    {
        return $formName . 'rule_conditions_fieldset_' . $this->getId();
    }

    /**
     * @param Product $product
     *
     * @return mixed|string
     */
    public function getApplyProduct(Product $product)
    {
        $ruleId = '';
        if ($this->getConditions()->validate($product)) {
            $ruleId = $this->getId();
        }

        return $ruleId;
    }

    /**
     * @return string
     */
    public function getTimeZone()
    {
        return $this->_localeDate->getConfigTimezone();
    }

    /**
     * @return Rules
     */
    protected function _afterLoad()
    {
        $cssBefore = $this->helperData->getJsonDecode($this->getCssBefore());
        $cssRunning = $this->helperData->getJsonDecode($this->getCssRunning());
        $this->addData($cssBefore);
        $this->addData($cssRunning);

        return parent::_afterLoad();
    }

    /**
     * @return Rules
     * @throws LocalizedException
     */
    public function beforeSave()
    {
        if ($this->getEnableBeforeStart()) {
            $cssBefore = $this->helperData->getJsonEncode(
                [
                    'title_color_before' => $this->getTitleColorBefore(),
                    'message_color_before' => $this->getMessageColorBefore(),
                    'clock_color_before' => $this->getClockColorBefore(),
                    'number_color_before' => $this->getNumberColorBefore(),
                    'text_color_before' => $this->getTextColorBefore()
                ]
            );
            $this->setCssBefore($cssBefore);
        }
        if ($this->getEnableWhileRunning()) {
            $cssRunning = $this->helperData->getJsonEncode(
                [
                    'title_color_running' => $this->getTitleColorRunning(),
                    'message_color_running' => $this->getMessageColorRunning(),
                    'clock_color_running' => $this->getClockColorRunning(),
                    'number_color_running' => $this->getNumberColorRunning(),
                    'text_color_running' => $this->getTextColorRunning()
                ]
            );
            $this->setCssRunning($cssRunning);
        }

        return parent::beforeSave();
    }

    /**
     * @inheritDoc
     */
    public function getRuleId()
    {
        return (int)$this->getData(self::RULE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setRuleId($value)
    {
        return $this->setData(self::RULE_ID, $value);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName($value)
    {
        return $this->setData(self::NAME, $value);
    }

    /**
     * @inheritDoc
     */
    public function getStatus()
    {
        return (bool)$this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus($value)
    {
        return $this->setData(self::STATUS, $value);
    }

    /**
     * @inheritDoc
     */
    public function getStoreIds()
    {
        return $this->getData(self::STORE_IDS);
    }

    /**
     * @inheritDoc
     */
    public function setStoreIds($value)
    {
        return $this->setData(self::STORE_IDS, $value);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerGroupIds()
    {
        return $this->getData(self::CUSTOMER_GROUP_IDS);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerGroupIds($value)
    {
        return $this->setData(self::CUSTOMER_GROUP_IDS, $value);
    }

    /**
     * @inheritDoc
     */
    public function getRuleType()
    {
        return (string)$this->getData(self::RULE_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setRuleType($value)
    {
        return $this->setData(self::RULE_TYPE, $value);
    }

    /**
     * @inheritDoc
     */
    public function getCatalogRuleId()
    {
        return (int)$this->getData(self::CATALOG_RULE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setCatalogRuleId($value)
    {
        return $this->setData(self::CATALOG_RULE_ID, $value);
    }

    /**
     * @inheritDoc
     */
    public function getFromDate()
    {
        return (string)$this->getData(self::FROM_DATE);
    }

    /**
     * @inheritDoc
     */
    public function setFromDate($value)
    {
        return $this->setData(self::FROM_DATE, $value);
    }

    /**
     * @inheritDoc
     */
    public function getToDate()
    {
        return (string)$this->getData(self::TO_DATE);
    }

    /**
     * @inheritDoc
     */
    public function setToDate($value)
    {
        return $this->setData(self::TO_DATE, $value);
    }

    /**
     * @inheritDoc
     */
    public function getPriority()
    {
        return (int)$this->getData(self::PRIORITY);
    }

    /**
     * @inheritDoc
     */
    public function setPriority($value)
    {
        return $this->setData(self::PRIORITY, $value);
    }

    /**
     * @inheritDoc
     */
    public function getPosition()
    {
        return (string)$this->getData(self::POSITION);
    }

    /**
     * @inheritDoc
     */
    public function setPosition($value)
    {
        return $this->setData(self::POSITION, $value);
    }

    /**
     * @inheritDoc
     */
    public function getEnableBeforeStart()
    {
        return (bool)$this->getData(self::ENABLE_BEFORE_START);
    }

    /**
     * @inheritDoc
     */
    public function setEnableBeforeStart($value)
    {
        return $this->setData(self::ENABLE_BEFORE_START, $value);
    }

    /**
     * @inheritDoc
     */
    public function getClockStyleBefore()
    {
        return (string)$this->getData(self::CLOCK_STYLE_BEFORE);
    }

    /**
     * @inheritDoc
     */
    public function setClockStyleBefore($value)
    {
        return $this->setData(self::CLOCK_STYLE_BEFORE, $value);
    }

    /**
     * @inheritDoc
     */
    public function getTemplateBeforeProduct()
    {
        return (string)$this->getData(self::TEMPLATE_BEFORE_PRODUCT);
    }

    /**
     * @inheritDoc
     */
    public function setTemplateBeforeProduct($value)
    {
        return $this->setData(self::TEMPLATE_BEFORE_PRODUCT, $value);
    }

    /**
     * @inheritDoc
     */
    public function getTemplateBeforeCategory()
    {
        return (string)$this->getData(self::TEMPLATE_BEFORE_CATEGORY);
    }

    /**
     * @inheritDoc
     */
    public function setTemplateBeforeCategory($value)
    {
        return $this->setData(self::TEMPLATE_BEFORE_CATEGORY, $value);
    }

    /**
     * @inheritDoc
     */
    public function getTitleColorBefore()
    {
        return $this->getData(self::TITLE_COLOR_BEFORE);
    }

    /**
     * @inheritDoc
     */
    public function setTitleColorBefore($value)
    {
        return $this->setData(self::TITLE_COLOR_BEFORE, $value);
    }

    /**
     * @inheritDoc
     */
    public function getMessageColorBefore()
    {
        return $this->getData(self::MESSAGE_COLOR_BEFORE);
    }

    /**
     * @inheritDoc
     */
    public function setMessageColorBefore($value)
    {
        return $this->setData(self::MESSAGE_COLOR_BEFORE, $value);
    }

    /**
     * @inheritDoc
     */
    public function getClockColorBefore()
    {
        return $this->getData(self::CLOCK_COLOR_BEFORE);
    }

    /**
     * @inheritDoc
     */
    public function setClockColorBefore($value)
    {
        return $this->setData(self::CLOCK_COLOR_BEFORE, $value);
    }

    /**
     * @inheritDoc
     */
    public function getNumberColorBefore()
    {
        return $this->getData(self::NUMBER_COLOR_BEFORE);
    }

    /**
     * @inheritDoc
     */
    public function setNumberColorBefore($value)
    {
        return $this->setData(self::CLOCK_COLOR_BEFORE, $value);
    }

    /**
     * @inheritDoc
     */
    public function getTextColorBefore()
    {
        return $this->getData(self::TEXT_COLOR_BEFORE);
    }

    /**
     * @inheritDoc
     */
    public function setTextColorBefore($value)
    {
        return $this->setData(self::TEXT_COLOR_BEFORE, $value);
    }

    /**
     * @inheritDoc
     */
    public function getEnableWhileRunning()
    {
        return (bool)$this->getData(self::ENABLE_WHILE_RUNNING);
    }

    /**
     * @inheritDoc
     */
    public function setEnableWhileRunning($value)
    {
        return $this->setData(self::ENABLE_WHILE_RUNNING, $value);
    }

    /**
     * @inheritDoc
     */
    public function getClockStyleRunning()
    {
        return $this->getData(self::CLOCK_STYLE_RUNNING);
    }

    /**
     * @inheritDoc
     */
    public function setClockStyleRunning($value)
    {
        return $this->setData(self::CLOCK_STYLE_RUNNING, $value);
    }

    /**
     * @inheritDoc
     */
    public function getTemplateRunningProduct()
    {
        return $this->getData(self::TEMPLATE_RUNNING_PRODUCT);
    }

    /**
     * @inheritDoc
     */
    public function setTemplateRunningProduct($value)
    {
        return $this->setData(self::TEMPLATE_RUNNING_PRODUCT, $value);
    }

    /**
     * @inheritDoc
     */
    public function getTemplateRunningCategory()
    {
        return $this->getData(self::TEMPLATE_RUNNING_CATEGORY);
    }

    /**
     * @inheritDoc
     */
    public function setTemplateRunningCategory($value)
    {
        return $this->setData(self::TEMPLATE_RUNNING_CATEGORY, $value);
    }

    /**
     * @inheritDoc
     */
    public function getTitleColorRunning()
    {
        return $this->getData(self::TITLE_COLOR_RUNNING);
    }

    /**
     * @inheritDoc
     */
    public function setTitleColorRunning($value)
    {
        return $this->setData(self::TITLE_COLOR_RUNNING, $value);
    }

    /**
     * @inheritDoc
     */
    public function getMessageColorRunning()
    {
        return $this->getData(self::MESSAGE_COLOR_RUNNING);
    }

    /**
     * @inheritDoc
     */
    public function setMessageColorRunning($value)
    {
        return $this->setData(self::MESSAGE_COLOR_RUNNING, $value);
    }

    /**
     * @inheritDoc
     */
    public function getClockColorRunning()
    {
        return $this->getData(self::CLOCK_COLOR_RUNNING);
    }

    /**
     * @inheritDoc
     */
    public function setClockColorRunning($value)
    {
        return $this->setData(self::CLOCK_COLOR_RUNNING, $value);
    }

    /**
     * @inheritDoc
     */
    public function getNumberColorRunning()
    {
        return $this->getData(self::NUMBER_COLOR_RUNNING);
    }

    /**
     * @inheritDoc
     */
    public function setNumberColorRunning($value)
    {
        return $this->setData(self::NUMBER_COLOR_RUNNING, $value);
    }

    /**
     * @inheritDoc
     */
    public function getTextColorRunning()
    {
        return $this->getData(self::TEXT_COLOR_RUNNING);
    }

    /**
     * @inheritDoc
     */
    public function setTextColorRunning($value)
    {
        return $this->setData(self::TEXT_COLOR_RUNNING, $value);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return (string)$this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return (string)$this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function getSaveAmount()
    {
        return (string)$this->getData(self::SAVE_AMOUNT);
    }

    /**
     * @inheritDoc
     */
    public function getSaveAmountValue()
    {
        return (string)$this->getData(self::SAVE_AMOUNT_VALUE);
    }

    /**
     * @inheritDoc
     */
    public function getSavePercent()
    {
        return (string)$this->getData(self::SAVE_PERCENT);
    }

    /**
     * @inheritDoc
     */
    public function getRemainingTime()
    {
        return $this->getData(self::REMAINING_TIME);
    }

    /**
     * @inheritDoc
     */
    public function setRemainingTime($value)
    {
        return $this->setData(self::REMAINING_TIME, $value);
    }
}
