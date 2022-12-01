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

namespace Mageplaza\CountdownTimer\Block\Adminhtml\Rules\Edit;

use Exception;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Registry;

/**
 * Class Tabs
 *
 * @package Mageplaza\CountdownTimer\Block\Adminhtml\Rules\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * Tabs constructor.
     *
     * @param Context $context
     * @param EncoderInterface $jsonEncoder
     * @param Session $authSession
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        EncoderInterface $jsonEncoder,
        Session $authSession,
        Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $jsonEncoder, $authSession, $data);

        $this->registry = $registry;
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('mpcountdowntimer_rules_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Item Information'));
    }

    /**
     * @return \Magento\Backend\Block\Widget\Tabs
     * @throws Exception
     */
    protected function _beforeToHtml()
    {
        $this->addTab('general', [
            'label' => __('General'),
            'title' => __('General'),
            'content' => $this->getChildHtml('general'),
            'active' => true
        ]);

        $this->addTab('condition', [
            'label' => __('Conditions'),
            'title' => __('Conditions'),
            'content' => $this->getChildHtml('condition')
        ]);

        $this->addTab('display', [
            'label' => __('Display'),
            'title' => __('Display'),
            'content' => $this->getChildHtml('display')
        ]);

        return parent::_beforeToHtml();
    }
}
