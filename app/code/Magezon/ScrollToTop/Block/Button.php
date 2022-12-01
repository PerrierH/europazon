<?php
/**
 * Magezon
 *
 * This source file is subject to the Magezon Software License, which is available at https://www.magezon.com/license
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to https://www.magezon.com for more information.
 *
 * @category  Magezon
 * @package   Magezon_ScrollToTop
 * @copyright Copyright (C) 2021 Magezon (https://www.magezon.com)
 */
namespace Magezon\ScrollToTop\Block;

use Magento\Framework\View\Element\Template;

class Button extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Magezon_ScrollToTop::button.phtml';

    /**
     * @var string
     */
    protected $controllerName;

    /**
     * @var \Magento\Framework\App\Action\Context
     */
    protected $actionContext;

    /**
     * @var \Magezon\ScrollToTop\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Action\Context $actionContext
     * @param \Magezon\ScrollToTop\Helper\Data $dataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Action\Context $actionContext,
        \Magezon\ScrollToTop\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->controllerName = $actionContext->getRequest()->getControllerName();
        $this->dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    public function _toHtml()
    {
        $currentStore = $this->_storeManager->getStore();
        $currentUrl = $currentStore->getCurrentUrl();
        $status = $this->dataHelper->getButtonDisplay($this->dataHelper->getDisplayPages(), $currentUrl, $this->dataHelper->getCustomDisplayPages());
        return $status ? parent::_toHtml() : "";
    }
}