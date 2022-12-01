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
 * Interface RuleInterface
 * @package Mageplaza\CountdownTimer\Api\Data
 */
interface RuleInterface
{
    const RULE_ID                   = 'rule_id';
    const NAME                      = 'name';
    const STATUS                    = 'status';
    const STORE_IDS                 = 'store_ids';
    const CUSTOMER_GROUP_IDS        = 'customer_group_ids';
    const RULE_TYPE                 = 'rule_type';
    const CATALOG_RULE_ID           = 'catalog_rule_id';
    const FROM_DATE                 = 'from_date';
    const TO_DATE                   = 'to_date';
    const PRIORITY                  = 'priority';
    const POSITION                  = 'position';
    const ENABLE_BEFORE_START       = 'enable_before_start';
    const CLOCK_STYLE_BEFORE        = 'clock_style_before';
    const TEMPLATE_BEFORE_PRODUCT   = 'template_before_product';
    const TEMPLATE_BEFORE_CATEGORY  = 'template_before_category';
    const TITLE_COLOR_BEFORE        = 'title_color_before';
    const MESSAGE_COLOR_BEFORE      = 'message_color_before';
    const CLOCK_COLOR_BEFORE        = 'clock_color_before';
    const NUMBER_COLOR_BEFORE       = 'number_color_before';
    const TEXT_COLOR_BEFORE         = 'text_color_before';
    const ENABLE_WHILE_RUNNING      = 'enable_while_running';
    const CLOCK_STYLE_RUNNING       = 'clock_style_running';
    const TEMPLATE_RUNNING_PRODUCT  = 'template_running_product';
    const TEMPLATE_RUNNING_CATEGORY = 'template_running_category';
    const TITLE_COLOR_RUNNING       = 'title_color_running';
    const MESSAGE_COLOR_RUNNING     = 'message_color_running';
    const CLOCK_COLOR_RUNNING       = 'clock_color_running';
    const NUMBER_COLOR_RUNNING      = 'number_color_running';
    const TEXT_COLOR_RUNNING        = 'text_color_running';
    const UPDATED_AT                = 'updated_at';
    const CREATED_AT                = 'created_at';
    const SAVE_AMOUNT               = 'save_amount';
    const SAVE_AMOUNT_VALUE         = 'save_amount_value';
    const SAVE_PERCENT              = 'save_percent';
    const TIMEZONE                  = 'timezone';
    const REMAINING_TIME            = 'remaining_time';

    /**
     * @return int
     */
    public function getRuleId();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setRuleId($value);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setName($value);

    /**
     * @return bool
     */
    public function getStatus();

    /**
     * @param int|bool|string $value
     *
     * @return $this
     */
    public function setStatus($value);

    /**
     * @return string
     */
    public function getStoreIds();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setStoreIds($value);

    /**
     * @return string
     */
    public function getCustomerGroupIds();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setCustomerGroupIds($value);

    /**
     * @return string
     */
    public function getRuleType();

    /**
     * @param string|int $value
     *
     * @return $this
     */
    public function setRuleType($value);

    /**
     * @return int
     */
    public function getCatalogRuleId();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setCatalogRuleId($value);

    /**
     * @return string
     */
    public function getFromDate();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setFromDate($value);

    /**
     * @return string
     */
    public function getToDate();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setToDate($value);

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setPriority($value);

    /**
     * @return string
     */
    public function getPosition();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setPosition($value);

    /**
     * @return bool
     */
    public function getEnableBeforeStart();

    /**
     * @param int|bool|string $value
     *
     * @return $this
     */
    public function setEnableBeforeStart($value);

    /**
     * @return string
     */
    public function getClockStyleBefore();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setClockStyleBefore($value);

    /**
     * @return string
     */
    public function getTemplateBeforeProduct();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setTemplateBeforeProduct($value);

    /**
     * @return string
     */
    public function getTemplateBeforeCategory();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setTemplateBeforeCategory($value);

    /**
     * @return string
     */
    public function getTitleColorBefore();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setTitleColorBefore($value);

    /**
     * @return string
     */
    public function getMessageColorBefore();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setMessageColorBefore($value);

    /**
     * @return string
     */
    public function getClockColorBefore();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setClockColorBefore($value);

    /**
     * @return string
     */
    public function getNumberColorBefore();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setNumberColorBefore($value);

    /**
     * @return string
     */
    public function getTextColorBefore();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setTextColorBefore($value);

    /**
     * @return bool
     */
    public function getEnableWhileRunning();

    /**
     * @param int|bool|string $value
     *
     * @return $this
     */
    public function setEnableWhileRunning($value);

    /**
     * @return string
     */
    public function getClockStyleRunning();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setClockStyleRunning($value);

    /**
     * @return string
     */
    public function getTemplateRunningProduct();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setTemplateRunningProduct($value);

    /**
     * @return string
     */
    public function getTemplateRunningCategory();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setTemplateRunningCategory($value);

    /**
     * @return string
     */
    public function getTitleColorRunning();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setTitleColorRunning($value);

    /**
     * @return string
     */
    public function getMessageColorRunning();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setMessageColorRunning($value);

    /**
     * @return string
     */
    public function getClockColorRunning();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setClockColorRunning($value);

    /**
     * @return string
     */
    public function getNumberColorRunning();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setNumberColorRunning($value);

    /**
     * @return string
     */
    public function getTextColorRunning();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setTextColorRunning($value);

    /**
     * @return string
     */
    public function getSaveAmount();

    /**
     * @return float
     */
    public function getSaveAmountValue();

    /**
     * @return string
     */
    public function getSavePercent();

    /**
     * @return string
     */
    public function getTimeZone();

    /**
     * @return string
     */
    public function getUpdatedAt();

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @return int
     */
    public function getRemainingTime();

    /**
     * @param int|string $value
     *
     * @return int
     */
    public function setRemainingTime($value);
}
