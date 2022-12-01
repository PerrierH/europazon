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
namespace Magezon\ScrollToTop\Block\Adminhtml;

use Magento\Backend\Block\Template;

class Button extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Magezon_ScrollToTop::button.phtml';
    
    /**
     * @var \Magezon\ScrollToTop\Helper\Data $dataHelper
     */
    protected $dataHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magezon\ScrollToTop\Helper\Data $dataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magezon\ScrollToTop\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    public function _toHtml() 
    {
        $currentStore = $this->_storeManager->getStore();
        $currentUrl = $currentStore->getCurrentUrl();
        $status = $this->dataHelper->getButtonDisplay($this->dataHelper->getDisplayOnBackend(), $currentUrl, $this->dataHelper->getCustomDisplayBackendPages());
        if (strpos($currentUrl, "scroll_to_top") !== false) {
            $status = true;
        }
        return $status ? parent::_toHtml() : "";
    }
}