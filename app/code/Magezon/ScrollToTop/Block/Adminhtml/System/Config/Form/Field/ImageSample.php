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
namespace Magezon\ScrollToTop\Block\Adminhtml\System\Config\Form\Field;

use Magento\Backend\Block\Template\Context;
use Magezon\ScrollToTop\Helper\Data;

class ImageSample extends \Magento\Config\Block\System\Config\Form\Field
{
     /**
     * @var Magezon\ScrollToTop\Helper\Data
     */
    private $dataHelper;

    /**
     * @param Context $context
     * @var Data $dataHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $dataHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->dataHelper = $dataHelper;
    }

    /**
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element 
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
     {
        $html = $element->getElementHtml();
        $value = $element->getData('value');
        $options = $this->getOptions();

        $html = <<<HTML
        <div class="stt-image-selected">
            <img src = "{$value}">
            <input id="imageSelected" type="hidden" name="{$element->getName()}" value="{$value}" />
        </div>
HTML;

        $html .= '<div class="stt-marker-image-wrapper"><div class="control stt-marker-images">';
        foreach ($options as $option) {
            $checked  = $value == $option['value'] ? 'active' : '';
            $html .= <<<HTML
            <div class="stt-image-template {$checked}" data-image="{$option['value']}">
                <img src ="{$option['value']}">
            </div>
HTML;
        }

        $html .= <<<HTML
            <script>
            require(['jquery'], function($) {
                $(".stt-image-selected").click(function() {
                    $(this).toggleClass('active');
                    $(".stt-marker-image-wrapper").toggleClass('active');
                });

                $(".stt-image-template").click(function() {
                    let imageCode = $(this).data("image");
                    $(".stt-image-selected img").remove();
                    $(".stt-image-selected").append('<img src="' + imageCode + '">');
                    $(".stt-image-selected input").val(imageCode);
                    $(".stt-image-template").removeClass('active');
                    $(this).addClass('active');
                });
            })
            </script>
HTML;

        $html .= '</div></div>';

        return $html;
    }

    /**
     * @return array
     */
    public function getOptions() 
    {
        $imageUrl = $this->dataHelper->getViewFileUrl("Magezon_ScrollToTop/images/default") . "/";
        $list = [];
        for ($i = 1; $i <=30; $i++) {   
            $key = $imageUrl . 'scrolltotop' . $i . '.png'; 
            $list[$key] = 'scrolltotop' . $i . '.png';
        }

        $options = [];
        foreach ($list as $image => $label) {
            $options[] = [
                'label' => $label,
                'value' => $image
            ];
        }
        return $options;
    }
}