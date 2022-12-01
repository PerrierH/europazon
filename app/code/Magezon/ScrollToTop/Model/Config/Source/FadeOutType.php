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

class FadeOutType implements \Magento\Framework\Option\ArrayInterface
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
            ['value' => 'backOutDown', 'label' => __('Back Out Down')],
            ['value' => 'backOutLeft', 'label' => __('Back Out Left')],
            ['value' => 'backOutRight', 'label' => __('Back Out Right')],
            ['value' => 'backOutUp', 'label' => __('Back Out Up')],
            ['value' => 'bounceOut', 'label' => __('Bounce Out')],
            ['value' => 'bounceOutDown', 'label' => __('Bounce Out Down')],
            ['value' => 'bounceOutLeft', 'label' => __('Bounce Out Left')],
            ['value' => 'bounceOutRight', 'label' => __('Bounce Out Right')],
            ['value' => 'bounceOutUp', 'label' => __('Bounce Out Up')],
            ['value' => 'fadeOut', 'label' => __('Fade Out')], 
            ['value' => 'fadeOutBottomLeft', 'label' => __('Fade Out Bottom Left')],
            ['value' => 'fadeOutBottomRight', 'label' => __('Fade Out Bottom Right')],
            ['value' => 'fadeOutTopLeft', 'label' => __('Fade Out Top Left')],
            ['value' => 'fadeOutTopRight', 'label' => __('Fade Out Top Right')],
            ['value' => 'fadeOutDown', 'label' => __('Fade Out Down')],
            ['value' => 'fadeOutDownBig', 'label' => __('Fade Out Down Big')],
            ['value' => 'fadeOutLeft', 'label' => __('Fade Out Left')],
            ['value' => 'fadeOutLeftBig', 'label' => __('Fade Out Left Big')],
            ['value' => 'fadeOutRight', 'label' => __('Fade Out Right')],
            ['value' => 'fadeOutRightBig', 'label' => __('Fade Out Right Big')],
            ['value' => 'fadeOutUp', 'label' => __('Fade Out Up')],
            ['value' => 'fadeOutUpBig', 'label' => __('Fade Out Up Big')],
            ['value' => 'flipOutX', 'label' => __('FlipOut X')],
            ['value' => 'flipOutY', 'label' => __('FlipOut Y')],
            ['value' => 'hinge', 'label' => __('Hinge')],
            ['value' => 'lightSpeedOutLeft', 'label' => __('Light Speed Out Left')],
            ['value' => 'lightSpeedOutRight', 'label' => __('Light Speed Out Right')],
            ['value' => 'rollOut', 'label' => __('Roll Out')],
            ['value' => 'rotateOut', 'label' => __('Rotate Out')],
            ['value' => 'rotateOutDownLeft', 'label' => __('Rotate Out Down Left')],
            ['value' => 'rotateOutDownRight', 'label' => __('Rotate Out Down Right')],
            ['value' => 'rotateOutUpLeft', 'label' => __('Rotate Out Up Left')],
            ['value' => 'rotateOutUpRight', 'label' => __('Rotate Out Up Right')],
            ['value' => 'slideOutDown', 'label' => __('Slide Out Down')],
            ['value' => 'slideOutLeft', 'label' => __('Slide Out Left')],
            ['value' => 'slideOutRight', 'label' => __('Slide Out Right')],
            ['value' => 'slideOutUp', 'label' => __('Slide Out Up')],
            ['value' => 'zoomOut', 'label' => __('Zoom Out')],
            ['value' => 'zoomOutDown', 'label' => __('Zoom Out Down')],
            ['value' => 'zoomOutLeft', 'label' => __('Zoom Out Left')],
            ['value' => 'zoomOutRight', 'label' => __('Zoom Out Right')],
            ['value' => 'zoomOutUp', 'label' => __('Zoom Out Up')]
        ];
    }
}