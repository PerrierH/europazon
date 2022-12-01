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

namespace Mageplaza\CountdownTimer\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\DateTime;
use Magento\Framework\View\Result\PageFactory;
use Mageplaza\CountdownTimer\Helper\Data;
use Mageplaza\CountdownTimer\Model\ResourceModel\Rules as ResourceModelRules;
use Mageplaza\CountdownTimer\Model\RulesFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Rules
 *
 * @package Mageplaza\CountdownTimer\Controller\Adminhtml
 */
abstract class Rules extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var RulesFactory
     */
    protected $rulesFactory;

    /**
     * @var ResourceModelRules
     */
    protected $resourceModel;

    /**
     * @var DateTime
     */
    protected $_dateFilter;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Rules constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $coreRegistry
     * @param RulesFactory $rulesFactory
     * @param ResourceModelRules $resourceModel
     * @param DateTime $date
     * @param Data $helperData
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        RulesFactory $rulesFactory,
        ResourceModelRules $resourceModel,
        DateTime $date,
        Data $helperData,
        LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $coreRegistry;
        $this->rulesFactory = $rulesFactory;
        $this->resourceModel = $resourceModel;
        $this->_dateFilter = $date;
        $this->helperData = $helperData;
        $this->logger = $logger;

        parent::__construct($context);
    }

    /**
     * Init layout, menu and breadcrumb
     *
     * @return Page
     */
    protected function _initAction()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->addBreadcrumb(_('Countdown Timer'), __('Manage Rules'));

        return $resultPage;
    }
}
