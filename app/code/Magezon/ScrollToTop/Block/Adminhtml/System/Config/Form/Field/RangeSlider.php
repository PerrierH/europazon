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

class RangeSlider extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element 
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = $element->getElementHtml();
        $value = $element->getData('value');

        $html .= <<<HTML
            <div class="slidecontainer">
                <input type="range" min="0" max="500" name="{$element->getName()}" value="{$value}" class="stt-slider" id="{$element->getId()}-elem" range="0,500">
                <p>Value: <span id="{$element->getId()}-value"></span></p> 
            </div>
HTML;

        $html .= <<<HTML
            <script>
                require(['jquery'], function ($) {
                    $('#{$element->getId()}-value').html($('#{$element->getId()}-elem').val());
                    $('#{$element->getId()}-elem').on('input', function (e) {
                        $('#{$element->getId()}-value').html($(this).val());
                    });
                })
            </script>
HTML;

        return $html;
    }
}
