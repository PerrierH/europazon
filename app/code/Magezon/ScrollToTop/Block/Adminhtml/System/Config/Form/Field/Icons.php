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

class Icons extends \Magento\Config\Block\System\Config\Form\Field
{
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
            <div class="stt-icon-selected">
                <i class="{$value}"></i>
                <input id="iconSelected" type="hidden" name="{$element->getName()}" value="{$value}" />
            </div>
HTML;

        $html .= '<div class="stt-marker-icon-wrapper"><input type="text" id="stt-icon-search" /><div class="control stt-marker-icons">';
        foreach ($options as $option) {
            $checked  = $value == $option['value'] ? 'active' : '';
            $html .= <<<HTML
            <div class="stt-icon-template {$checked}" data-icon="{$option['value']}">
                <i class="{$option['value']}"></i>
            </div>
HTML;
        }

        $html .= <<<HTML
            <script>
            require(['jquery'], function($) {
                $(".stt-icon-selected").click(function() {
                    $(this).toggleClass('active');
                    $(".stt-marker-icon-wrapper").toggleClass('active');
                });

                $(".stt-icon-template").click(function() {
                    let iconCode = $(this).data("icon");
                    $(".stt-icon-selected i").remove();
                    $(".stt-icon-selected").append('<i class="' + iconCode + '"></i>');
                    $(".stt-icon-selected input").val(iconCode);
                    $(".stt-icon-template").removeClass('active');
                    $(this).addClass('active');
                });

                $("#stt-icon-search").keyup(function() {
                    let value = $(this).val();
                    $(".stt-icon-template").each(function(index, el) {
                        let iconCode = $(this).data("icon");

                        if (iconCode.indexOf(value) !== -1 || !value) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
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
        $list = [
            'far mgz-fa-arrow-alt-circle-up' => 'arrow-alt-circle-up',
            'fas mgz-fa-arrow-circle-up' => 'arrow-circle-up',
            'fas mgz-fa-arrow-up' => 'arrow-up',
            'fas mgz-fa-sort-up' => 'sort-up',
            'fas mgz-fa-chevron-up' => 'chevron-up',
            'fas mgz-fa-caret-up' => 'caret-up',
            'fas mgz-fa-angle-up' => 'angle-up',
            'fas mgz-fa-hand-point-up' => 'hand-point-up',
            'fas mgz-fa-chevron-circle-up' => 'chevron-circle-up',
            'fas mgz-fa-long-arrow-alt-up' => 'long-arrow-alt-up', 
            'fas mgz-fa-caret-square-up' => 'caret-square-up', 
            'fas mgz-fa-angle-double-up' => 'angle-double-up', 
            'fas mgz-fa-eject' => 'eject', 
            'fas mgz-fa-hand-pointer' => 'hand-pointer', 
        ];

        $options = [];
        foreach ($list as $icon => $label) {
            $options[] = [
                'label' => $label,
                'value' => $icon
            ];
        }
        return $options;
    }
}