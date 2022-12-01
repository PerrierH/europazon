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
 * @package     Mageplaza_ProductLabels
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ProductLabels\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\Core\Helper\AbstractData;
use Mageplaza\ProductLabels\Model\Indexer\RuleIndexer;
use Mageplaza\ProductLabels\Model\MetaFactory;
use Mageplaza\ProductLabels\Model\RuleFactory;


/**
 * Class HelperConsole
 * @package Mageplaza\ProductLabels\Helper
 */
class HelperConsole extends AbstractData
{
    const CONFIG_MODULE_PATH = 'productlabels';

    /**
     * @var RuleFactory
     */
    private $ruleModel;

    /**
     * @var MetaFactory
     */
    private $metaModel;

    /**
     * @var RuleIndexer
     */
    private $ruleIndexerModel;

    /**
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     * @param RuleFactory $ruleFactory
     * @param MetaFactory $metaFactory
     * @param RuleIndexer $ruleIndexer
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        RuleFactory $ruleFactory,
        MetaFactory $metaFactory,
        RuleIndexer $ruleIndexer
    ) {
        $this->ruleModel        = $ruleFactory;
        $this->metaModel        = $metaFactory;
        $this->ruleIndexerModel = $ruleIndexer;

        parent::__construct($context, $objectManager, $storeManager);
    }

    /**
     * @return RuleFactory
     */
    public function getRuleModel()
    {
        return $this->ruleModel;
    }

    /**
     * @return MetaFactory
     */
    public function getMetaModel()
    {
        return $this->metaModel;
    }

    /**
     * @return RuleIndexer
     */
    public function getRuleIndexerModel()
    {
        return $this->ruleIndexerModel;
    }
}
