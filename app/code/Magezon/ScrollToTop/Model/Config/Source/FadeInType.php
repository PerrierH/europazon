<?php
/**
 * Magezon
 *
 * This source file is subject to the Magezon Software License, which is available at https://www.magezon.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to https://www.magezon.com for more information.
 *
 * @category  Magezon
 * @package   Magezon_ScrollToTop
 * @copyright Copyright (C) 2021 Magezon (https://www.magezon.com)
 */
namespace Magezon\ScrollToTop\Model\Config\Source;

class FadeInType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [ 
            ['value' => 'none', 'label' => __('None')], 
            ['value' => 'backInDown', 'label' => __('Back In Down')],
            ['value' => 'backInLeft', 'label' => __('Back In Left')],
            ['value' => 'backInRight', 'label' => __('Back In Right')],
            ['value' => 'backInUp', 'label' => __('Back In Up')],
            ['value' => 'bounce', 'label' => __('Bounce')], 
            ['value' => 'bounceIn', 'label' => __('Bounce In')],
            ['value' => 'bounceInDown', 'label' => __('Bounce In Down')],
            ['value' => 'bounceInLeft', 'label' => __('Bounce In Left')],
            ['value' => 'bounceInRight', 'label' => __('Bounce In Right')],
            ['value' => 'bounceInUp', 'label' => __('Bounce In Up')],
            ['value' => 'fadeIn', 'label' => __('Fade In')], 
            ['value' => 'fadeInBottomLeft', 'label' => __('Fade In Bottom Left')],
            ['value' => 'fadeInBottomRight', 'label' => __('Fade In Bottom Right')],
            ['value' => 'fadeInTopLeft', 'label' => __('Fade In Top Left')],
            ['value' => 'fadeInTopRight', 'label' => __('Fade In Top Right')],
            ['value' => 'fadeInDown', 'label' => __('Fade In Down')],
            ['value' => 'fadeInDownBig', 'label' => __('Fade In Down Big')],
            ['value' => 'fadeInLeft', 'label' => __('Fade In Left')],
            ['value' => 'fadeInLeftBig', 'label' => __('Fade In Left Big')],
            ['value' => 'fadeInRight', 'label' => __('Fade In Right')],
            ['value' => 'fadeInRightBig', 'label' => __('Fade In Right Big')],
            ['value' => 'fadeInUp', 'label' => __('Fade In Up')],
            ['value' => 'fadeInUpBig', 'label' => __('Fade In Up Big')],
            ['value' => 'flash', 'label' => __('Flash')],  
            ['value' => 'flip', 'label' => __('Flip')],
            ['value' => 'flipInX', 'label' => __('FlipIn X')],
            ['value' => 'flipInY', 'label' => __('FlipIn Y')],
            ['value' => 'headShake', 'label' => __('Head Shake')],
            ['value' => 'heartBeat', 'label' => __('Heart Beat')],
            ['value' => 'rackInTheBox', 'label' => __('Jack In The Box')],
            ['value' => 'jello', 'label' => __('Jello')],
            ['value' => 'lightSpeedInLeft', 'label' => __('Light Speed In Left')],
            ['value' => 'lightSpeedInRight', 'label' => __('Light Speed In Right')],
            ['value' => 'pulse', 'label' => __('Pulse')],
            ['value' => 'rollIn', 'label' => __('Roll In')],
            ['value' => 'rotateIn', 'label' => __('Rotate In')],
            ['value' => 'rotateInDownLeft', 'label' => __('Rotate In Down Left')],
            ['value' => 'rotateInDownRight', 'label' => __('Rotate In Down Right')],
            ['value' => 'rotateInUpLeft', 'label' => __('Rotate In Up Left')],
            ['value' => 'rotateInUpRight', 'label' => __('Rotate In Up Right')],
            ['value' => 'rubberBand', 'label' => __('Rubber Band')],
            ['value' => 'slideInDown', 'label' => __('Slide In Down')],
            ['value' => 'slideInLeft', 'label' => __('Slide In Left')],
            ['value' => 'slideInRight', 'label' => __('Slide In Right')],
            ['value' => 'slideInUp', 'label' => __('Slide In Up')],
            ['value' => 'shakeX', 'label' => __('Shake X')],
            ['value' => 'shakeY', 'label' => __('Shake Y')],
            ['value' => 'swing', 'label' => __('Swing')],
            ['value' => 'tada', 'label' => __('Tada')],
            ['value' => 'wobble', 'label' => __('Wobble')],
            ['value' => 'zoomIn', 'label' => __('Zoom In')],
            ['value' => 'zoomInDown', 'label' => __('Zoom In Down')],
            ['value' => 'zoomInLeft', 'label' => __('Zoom In Left')],
            ['value' => 'zoomInRight', 'label' => __('Zoom In Right')],
            ['value' => 'zoomInUp', 'label' => __('Zoom In Up')]
        ];
    }
}