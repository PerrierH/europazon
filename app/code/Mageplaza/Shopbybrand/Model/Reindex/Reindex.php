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
 * @package     Mageplaza_Shopbybrand
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Shopbybrand\Model\Reindex;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Reindex
 * @package Mageplaza\Shopbybrand\Model\Reindex
 */
class Reindex extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'mageplaza_brand_reports_reindex';

    /**
     * @var string
     */
    protected $_cacheTag = 'mageplaza_brand_reports_reindex';

    /**
     * @var string
     */
    protected $_eventPrefix = 'mageplaza_brand_reports_reindex';

    /**
     * Initialize model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Mageplaza\Shopbybrand\Model\ResourceModel\Reindex\Reindex::class);
    }

    /**
     * @return array|string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
