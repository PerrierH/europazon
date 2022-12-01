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

namespace Mageplaza\CountdownTimer\Block\Adminhtml\Rules\Edit\Tab\Renderer;

use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Snippet
 *
 * @package Mageplaza\CountdownTimer\Block\Adminhtml\Rules\Edit\Tab\Renderer
 */
class Snippet extends AbstractElement
{
    /**
     * @return string
     */
    public function getElementHtml()
    {
        $subject = $this->getData('subject');

        $currentRule = $subject->getCurrentRule();
        $ruleId = $currentRule->getRuleId() ?: '';

        $html = '<div class="control-value" style="padding-top: 8px; font-size: 11px;"><p>';
        $html .= __('Use the following code to show Countdown Timer block at any places you want.');
        $html .= '</p><strong>';

        $html .= __('CMS Page/Static Block');
        $html .= '</strong><br /><pre style="background-color: #f5f5dc;">
                    <code>{{block class="Mageplaza\CountdownTimer\Block\Widget" rule_id ="'.$ruleId.'"}}</code>
                </pre><strong>';

        $html .= __('Template .phtml file');
        $html .= '</strong><br /><pre style="background-color: #f5f5dc;">
                    <code>' . $this->_escaper->escapeHtml(
                '<?php echo $block->getLayout()->createBlock(\Mageplaza\CountdownTimer\Block\Widget::class)
                    ->setData(["rule_id" => "'.$ruleId.'"])
                    ->toHtml();?>'
            ) . '</code>
                  </pre><strong>';

        $html .= __('Layout file');
        $html .= '</strong><br />
                    <pre style="background-color: #f5f5dc;">
                        <code>' . $this->_escaper->escapeHtml(
                '<block class="Mageplaza\CountdownTimer\Block\Widget" name="mp-countdown">
                            <arguments>
                                <argument name="rule_id" xsi:type="string">'.$ruleId.'</argument>
                            </arguments>
                        </block>'
            ) . '</code>
                    </pre>';

        $html .= '</div>';

        return $html;
    }
}
