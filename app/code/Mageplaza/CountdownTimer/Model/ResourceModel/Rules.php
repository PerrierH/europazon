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

namespace Mageplaza\CountdownTimer\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Rules
 *
 * @package Mageplaza\CountdownTimer\Model\ResourceModel
 */
class Rules extends AbstractDb
{
    /**
     * Date model
     *
     * @var DateTime
     */
    protected $_date;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Rules constructor.
     *
     * @param Context $context
     * @param DateTime $date
     * @param StoreManagerInterface $storeManager
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        DateTime $date,
        StoreManagerInterface $storeManager,
        $connectionName = null
    ) {
        $this->_date = $date;
        $this->_storeManager = $storeManager;

        parent::__construct($context, $connectionName);
    }

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mageplaza_countdown_timer_rules', 'rule_id');
    }

    /**
     * @param AbstractModel $object
     *
     * @return AbstractDb
     */
    protected function _beforeSave(AbstractModel $object)
    {
        //set default Update At and Create At time post
        $object->setData('update_at', $this->_date->date());
        if ($object->isObjectNew()) {
            $object->setData('create_at', $this->_date->date());
        }

        $storeIds = $object->getData('store_ids');
        if (is_array($storeIds)) {
            $object->setData('store_ids', implode(',', $storeIds));
        }

        $groupIds = $object->getData('customer_group_ids');
        if (is_array($groupIds)) {
            $object->setData('customer_group_ids', implode(',', $groupIds));
        }

        $position = $object->getData('position');
        if (is_array($position)) {
            $object->setData('position', implode(',', $position));
        }

        return parent::_beforeSave($object);
    }
}
