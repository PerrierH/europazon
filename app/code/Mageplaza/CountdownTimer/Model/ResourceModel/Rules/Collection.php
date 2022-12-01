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

namespace Mageplaza\CountdownTimer\Model\ResourceModel\Rules;

use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mageplaza\CountdownTimer\Model\Rules;
use Zend_Db_Select;

/**
 * Class Collection
 *
 * @package Mageplaza\CountdownTimer\Model\ResourceModel\Rules
 */
class Collection extends AbstractCollection
{
    /**
     * ID Field Name
     *
     * @var string
     */
    protected $_idFieldName = 'rule_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'mageplaza_countdown_timer_rules_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'rules_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Rules::class, \Mageplaza\CountdownTimer\Model\ResourceModel\Rules::class);
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @return Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);

        return $countSelect;
    }

    /**
     * @param string $customerGroup
     * @param string $storeId
     *
     * @return $this
     */
    public function addActiveFilter($customerGroup = null, $storeId = null)
    {
        $this->addFieldToFilter('status', true)->setOrder('priority', Select::SQL_DESC);

        if (isset($customerGroup)) {
            $this->getSelect()->where('FIND_IN_SET(?, customer_group_ids)', $customerGroup);
        }
        if (isset($storeId)) {
            $this->getSelect()->where('FIND_IN_SET(0, store_ids) OR FIND_IN_SET(?, store_ids)', $storeId);
        }

        return $this;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function addPageTypeFilter($type)
    {
        $this->getSelect()->where('FIND_IN_SET(?, position)', $type);

        return $this;
    }
}
