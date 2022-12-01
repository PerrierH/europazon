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

namespace Mageplaza\CountdownTimer\Block\Adminhtml\Rules\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Element\Dependence;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Framework\Data\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Mageplaza\CountdownTimer\Block\Adminhtml\Rules\Edit\Tab\Renderer\Snippet;
use Mageplaza\CountdownTimer\Model\Config\Source\ClockStyle;
use Mageplaza\CountdownTimer\Model\Config\Source\PageView;

/**
 * Class Display
 *
 * @package Mageplaza\CountdownTimer\Block\Adminhtml\Rules\Edit\Tab
 */
class Display extends Generic implements TabInterface
{
    /**
     * @var PageView
     */
    protected $pageView;

    /**
     * @var ClockStyle
     */
    protected $clockStyle;

    /**
     * @var Yesno
     */
    protected $statusOptions;

    /**
     * Display constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param PageView $pageView
     * @param ClockStyle $clockStyle
     * @param Yesno $statusOptions
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        PageView $pageView,
        ClockStyle $clockStyle,
        Yesno $statusOptions,
        array $data = []
    ) {
        $this->pageView = $pageView;
        $this->clockStyle = $clockStyle;
        $this->statusOptions = $statusOptions;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return mixed|null
     */
    public function getCurrentRule()
    {
        return $this->_coreRegistry->registry('mpcountdowntimer_rules');
    }

    /**
     * @return Generic
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.TooManyFields)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('mpcountdowntimer_rules');
        $templateSample1 = '<div class="mp-countdown-title">This product is discounting {{save_percent}}</div>
                            {{clock}}
                            <div class="mp-countdown-message">Hurry up!</div>';
        $templateSample2 = '<div class="mp-countdown-title">Discounting {{save_percent}}. Hurry up!</div>
                            {{clock}}';

        /** @var Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('mpcountdowntimer_');
        $form->setFieldNameSuffix('mpcountdowntimer');

        $fieldset = $form->addFieldset('display_fieldset', [
            'legend' => __('Display'),
            'collapsable' => true
        ]);

        $fieldset->addField('position', 'multiselect', [
            'name' => 'position',
            'label' => __('Display On'),
            'title' => __('Display On'),
            'values' => $this->pageView->toOptionArray()
        ]);

        $fieldset->addField('snippet_code', Snippet::class, [
            'name' => 'snippet_code',
            'label' => 'Snippet Code',
            'title' => 'Snippet Code',
            'subject' => $this
        ]);

        //   custom clock before
        $sub_fieldset_1 = $form->addFieldset('sub_fieldset_1', [
            'legend' => __('Before Starting Countdown'),
            'collapsable' => true
        ]);

        $enableBefore = $sub_fieldset_1->addField('enable_before_start', 'select', [
            'name' => 'enable_before_start',
            'label' => __('Enable'),
            'title' => __('Enable'),
            'values' => $this->statusOptions->toOptionArray()
        ]);

        $styleBefore = $sub_fieldset_1->addField('clock_style_before', 'select', [
            'name' => 'clock_style_before',
            'label' => __('Clock Style'),
            'title' => __('Clock Style'),
            'values' => $this->clockStyle->toOptionArray()
        ]);

        $templateBeforeProduct = $sub_fieldset_1->addField('template_before_product', 'textarea', [
            'name' => 'template_before_product',
            'label' => __('Template on Product View'),
            'title' => __('Template on Product View'),
            'required' => true,
            'note' => __('Use the variables {{clock}}, {{save_amount}} & {{save_percent}} for the template')
        ]);

        $templateBeforeCategory = $sub_fieldset_1->addField('template_before_category', 'textarea', [
            'name' => 'template_before_category',
            'label' => __('Template on Category View'),
            'title' => __('Template on Category View'),
            'required' => true,
            'note' => __('Use the variables {{clock}}, {{save_amount}} & {{save_percent}} for the template')
        ]);

        $titleColorBefore = $sub_fieldset_1->addField('title_color_before', 'text', [
            'name' => 'title_color_before',
            'label' => __('Title Color'),
            'title' => __('Title Color'),
            'class' => 'jscolor {hash:true,refine:false}'
        ]);

        $messageColorBefore = $sub_fieldset_1->addField('message_color_before', 'text', [
            'name' => 'message_color_before',
            'label' => __('Message Color'),
            'title' => __('Message Color'),
            'class' => 'jscolor {hash:true,refine:false}'
        ]);

        $clockColorBefore = $sub_fieldset_1->addField('clock_color_before', 'text', [
            'name' => 'clock_color_before',
            'label' => __('Clock Background Color'),
            'title' => __('Clock Background Color'),
            'class' => 'jscolor {hash:true,refine:false}'
        ]);

        $numberColorBefore = $sub_fieldset_1->addField('number_color_before', 'text', [
            'name' => 'number_color_before',
            'label' => __('Number Color'),
            'title' => __('Number Color'),
            'class' => 'jscolor {hash:true,refine:false}'
        ]);

        $textColorBefore = $sub_fieldset_1->addField('text_color_before', 'text', [
            'name' => 'text_color_before',
            'label' => __('Text Color'),
            'title' => __('Text Color'),
            'class' => 'jscolor {hash:true,refine:false}'
        ]);

        //      custom clock running
        $sub_fieldset_2 = $form->addFieldset('sub_fieldset_2', [
            'legend' => __('Time Remaining Countdown'),
            'collapsable' => true
        ]);

        $enableRunning = $sub_fieldset_2->addField('enable_while_running', 'select', [
            'name' => 'enable_while_running',
            'label' => __('Enable'),
            'title' => __('Enable'),
            'values' => $this->statusOptions->toOptionArray()
        ]);

        $styleRunning = $sub_fieldset_2->addField('clock_style_running', 'select', [
            'name' => 'clock_style_running',
            'label' => __('Clock Style'),
            'title' => __('Clock Style'),
            'values' => $this->clockStyle->toOptionArray()
        ]);

        $templateRunningProduct = $sub_fieldset_2->addField('template_running_product', 'textarea', [
            'name' => 'template_running_product',
            'label' => __('Template on Product View'),
            'title' => __('Template on Product View'),
            'required' => true,
            'note' => __('Use the variables {{clock}}, {{save_amount}} & {{save_percent}} for the template')
        ]);

        $templateRunningCategory = $sub_fieldset_2->addField('template_running_category', 'textarea', [
            'name' => 'template_running_category',
            'label' => __('Template on Category View'),
            'title' => __('Template on Category View'),
            'required' => true,
            'note' => __('Use the variables {{clock}}, {{save_amount}} & {{save_percent}} for the template')
        ]);

        $titleColorRunning = $sub_fieldset_2->addField('title_color_running', 'text', [
            'name' => 'title_color_running',
            'label' => __('Title Color'),
            'title' => __('Title Color'),
            'class' => 'jscolor {hash:true,refine:false}'
        ]);

        $messageColorRunning = $sub_fieldset_2->addField('message_color_running', 'text', [
            'name' => 'message_color_running',
            'label' => __('Message Color'),
            'title' => __('Message Color'),
            'class' => 'jscolor {hash:true,refine:false}'
        ]);

        $clockColorRunning = $sub_fieldset_2->addField('clock_color_running', 'text', [
            'name' => 'clock_color_running',
            'label' => __('Clock Background Color'),
            'title' => __('Clock Background Color'),
            'class' => 'jscolor {hash:true,refine:false}'
        ]);

        $numberColorRunning = $sub_fieldset_2->addField('number_color_running', 'text', [
            'name' => 'number_color_running',
            'label' => __('Number Color'),
            'title' => __('Number Color'),
            'class' => 'jscolor {hash:true,refine:false}'
        ]);

        $textColorRunning = $sub_fieldset_2->addField('text_color_running', 'text', [
            'name' => 'text_color_running',
            'label' => __('Text Color'),
            'title' => __('Text Color'),
            'class' => 'jscolor {hash:true,refine:false}'
        ]);

        if (!$model->getId()) {
            $sampleData = [
                'template_before_product' => $templateSample1,
                'template_before_category' => $templateSample2,
                'title_color_before' => '#eb5202',
                'message_color_before' => '#eb5202',
                'number_color_before' => '#eb5202',
                'text_color_before' => '#eb5202',
                'template_running_product' => $templateSample1,
                'template_running_category' => $templateSample2,
                'title_color_running' => '#eb5202',
                'message_color_running' => '#eb5202',
                'number_color_running' => '#eb5202',
                'text_color_running' => '#eb5202'
            ];
            $model->setData($sampleData);
        }

        /** @var Dependence $dependencies */
        $dependencies = $this->getLayout()->createBlock(Dependence::class);
        $dependencies->addFieldMap($enableBefore->getHtmlId(), $enableBefore->getName())
            ->addFieldMap($styleBefore->getHtmlId(), $styleBefore->getName())
            ->addFieldMap($templateBeforeProduct->getHtmlId(), $templateBeforeProduct->getName())
            ->addFieldMap($templateBeforeCategory->getHtmlId(), $templateBeforeCategory->getName())
            ->addFieldMap($titleColorBefore->getHtmlId(), $titleColorBefore->getName())
            ->addFieldMap($messageColorBefore->getHtmlId(), $messageColorBefore->getName())
            ->addFieldMap($clockColorBefore->getHtmlId(), $clockColorBefore->getName())
            ->addFieldMap($numberColorBefore->getHtmlId(), $numberColorBefore->getName())
            ->addFieldMap($textColorBefore->getHtmlId(), $textColorBefore->getName())
            ->addFieldMap($enableRunning->getHtmlId(), $enableRunning->getName())
            ->addFieldMap($styleRunning->getHtmlId(), $styleRunning->getName())
            ->addFieldMap($templateRunningProduct->getHtmlId(), $templateRunningProduct->getName())
            ->addFieldMap($templateRunningCategory->getHtmlId(), $templateRunningCategory->getName())
            ->addFieldMap($titleColorRunning->getHtmlId(), $titleColorRunning->getName())
            ->addFieldMap($messageColorRunning->getHtmlId(), $messageColorRunning->getName())
            ->addFieldMap($clockColorRunning->getHtmlId(), $clockColorRunning->getName())
            ->addFieldMap($numberColorRunning->getHtmlId(), $numberColorRunning->getName())
            ->addFieldMap($textColorRunning->getHtmlId(), $textColorRunning->getName())
            ->addFieldDependence($styleBefore->getName(), $enableBefore->getName(), 1)
            ->addFieldDependence($templateBeforeProduct->getName(), $enableBefore->getName(), 1)
            ->addFieldDependence($templateBeforeCategory->getName(), $enableBefore->getName(), 1)
            ->addFieldDependence($titleColorBefore->getName(), $enableBefore->getName(), 1)
            ->addFieldDependence($messageColorBefore->getName(), $enableBefore->getName(), 1)
            ->addFieldDependence($numberColorBefore->getName(), $enableBefore->getName(), 1)
            ->addFieldDependence($textColorBefore->getName(), $enableBefore->getName(), 1)
            ->addFieldDependence($styleRunning->getName(), $enableRunning->getName(), 1)
            ->addFieldDependence($templateRunningProduct->getName(), $enableRunning->getName(), 1)
            ->addFieldDependence($templateRunningCategory->getName(), $enableRunning->getName(), 1)
            ->addFieldDependence($numberColorRunning->getName(), $enableRunning->getName(), 1)
            ->addFieldDependence($textColorRunning->getName(), $enableRunning->getName(), 1)
            ->addFieldDependence($messageColorRunning->getName(), $enableRunning->getName(), 1)
            ->addFieldDependence($titleColorRunning->getName(), $enableRunning->getName(), 1);
        // define field dependencies
        $this->setChild('form_after', $dependencies);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Display');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}
