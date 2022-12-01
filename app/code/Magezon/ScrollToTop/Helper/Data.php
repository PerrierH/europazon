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
namespace Magezon\ScrollToTop\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\App\Action\Context as ActionContext;
use Magezon\Core\Helper\Data as CoreHelper;

class Data extends AbstractHelper
{
     /**
     * @var Magezon\Core\Helper\Data
     */
    private $coreHelper;

     /**
     * @var Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

     /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $assetRepo;

    /**
     * @var \Magento\Framework\App\Action\Context 
     */
    protected $actionContext;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param Repository $assetRepo
     * @param ActionContext $actionContext
     * @param CoreHelper $coreHelper
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Repository $assetRepo,
        ActionContext $actionContext,
        CoreHelper $coreHelper
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->assetRepo = $assetRepo;
        $this->controllerName = $actionContext->getRequest()->getControllerName();
        $this->coreHelper = $coreHelper;
    }

    /**
     * @param string $key
     * @param null|int $_store
     * 
     * @return null|string
     */
    public function getConfig($key, $_store = null)
    {
        $store = $this->storeManager->getStore($_store);
        $result = $this->scopeConfig->getValue(
            'scroll_to_top/' . $key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
        return $result;
    }

    /**
     * get enable on small device or not
     * 
     * @return string
     */
    public function getHideOnSmallDevice()
    {
        return $this->getConfig('general/hide_on_small_device');
    }

    /**
     * get small device width by pixel
     * 
     * @return string
     */
    public function getSmallDeviceWidth()
    {
        return $this->getConfig('general/small_device_width');
    }

    /**
     * customer choose the button show on: all page or customize
     * 
     * @return string
     */
    public function getDisplayPages()
    {
        return $this->getConfig('general/display_on_fontend');
    }

    /**
     * customer choose the button show on backend or not
     * 
     * @return string
     */
    public function getDisplayOnBackend()
    {
        return $this->getConfig('general/display_on_backend');
    }

    /**
     * Get pages where button show
     * 
     * @return array
     */
    public function getCustomDisplayPages()
    {
        return $this->coreHelper->unserialize($this->getConfig('general/custom_display_pages'));
    }

    /**
     * Get pages where button show
     * 
     * @return array
     */
    public function getCustomDisplayBackendPages()
    {
        return $this->coreHelper->unserialize($this->getConfig('general/custom_display_backend_pages'));
    }

     /**
     * Get custom css from customer
     * 
     * @return string
     */
    public function getCustomCss()
    {
        return $this->getConfig('general/css_field');
    }

    /**
     * Get display type
     * 
     * @return string
     */
    public function getDisplayType()
    {
        return $this->getConfig('display/display_type');
    }

    /**
     * Get Background color
     * 
     * @return string
     */
    public function getBackGroundColor()
    {
        return $this->getConfig('display/background_color') ? $this->getConfig('display/background_color') : 'transparent';
    }

    /**
     * Get Background color on hover
     * 
     * @return string
     */
    public function getBackGroundHover()
    {
         return $this->getConfig('display/background_color_hover') ? $this->getConfig('display/background_color_hover') : 'transparent';
    }

    /**
     * Get size of button
     * 
     * @return string
     */
    public function getButttonSize()
    {
        return $this->getConfig('display/resize_button');
    }

    /**
     * Get text on button
     * 
     * @return string
     */
    public function getText()
    {
        return $this->getConfig('display/text/text_on_button');
    }

    /**
     * Get text size
     * 
     * @return string
     */
    public function getTextSize()
    {
        return $this->getConfig('display/text/text_size');
    }

    /**
     * Get text color
     * 
     * @return string
     */
    public function getTextColor()
    {
        return $this->getConfig('display/text/text_color') ? $this->getConfig('display/text/text_color') : 'transparent';
    }
    
    /**
     * Get text color on hover
     * 
     * @return string
     */
    public function getTextColorHover()
    {
        return $this->getConfig('display/text/text_color_hover') ? $this->getConfig('display/text/text_color_hover') : 'transparent';
    }

    /**
     * Get image type
     * 
     * @return string
     */
    public function getImageType()
    {
        return $this->getConfig('display/image/image_type');
    }

    /**
     * Get image from computer
     * 
     * @return string
     */
    public function getupload()
    {
        return $this->coreHelper->getMediaUrl() . 'scroll_to_top/image/' . $this->getConfig('display/image/upload_image');
    }

    /**
     * Get image from URL
     * 
     * @return string
     */
    public function getImageUrl()
    {
        return $this->getConfig('display/image/image_url');
    }

    /**
     * Get image from sample
     * 
     * @return string
     */
    public function getImageSample()
    {
        return $this->getConfig('display/image/image_sample');
    }

    /**
     * Get image alt
     * 
     * @return string
     */
    public function getImageAlt()
    {
        return $this->getConfig('display/image/image_alt');
    }

    /**
     * Get icon 
     * 
     * @return string
     */
    public function getIcon()
    {
        return $this->getConfig('display/icon/marker_icon');
    }

    /**
     * Get icon color
     * 
     * @return string
     */
    public function getIconColor()
    {
        return $this->getConfig('display/icon/icon_color') ? $this->getConfig('display/icon/icon_color') : 'transparent';
    }

    /**
     * Get icon color on hover
     * 
     * @return string
     */
    public function getIconColorHover()
    {
        return $this->getConfig('display/icon/icon_color_hover') ? $this->getConfig('display/icon/icon_color_hover') : 'transparent';
    }
    
    /**
     * Get position on which corner
     * 
     * @return string
     */
    public function getButtonPosition()
    {
        return $this->getConfig('position/button_position');
    }

    /**
     * Get margin x of button
     * 
     * @return string
     */
    public function getMarginX()
    {
        return $this->getConfig('position/margin_x');
    }

    /**
     * Get margin y of button
     * 
     * @return string
     */
    public function getMarginY()
    {
        return $this->getConfig('position/margin_y');
    }

    /**
     * Get distance type
     * 
     * @return string
     */
    public function getDistanceType()
    {
        return $this->getConfig('display/animations/distance_type');   
    }

    /**
     * Get distance form top of page to where button start appearing by pixel
     * 
     * @return string
     */
    public function getScrollDistancePixel()
    {
        return  $this->getConfig('display/animations/scroll_distance_pixel');
    }

    /**
     * Get distance form top of page to where button start appearing by percent
     * 
     * @return string
     */
    public function getScrollDistancePercent()
    {
        return $this->getConfig('display/animations/scroll_distance_percent')/100;
    }

    /**
     * Windown scroll up speed
     * 
     * @return string
     */
    public function getScrollSpeed()
    {   
        $speedType = $this->getConfig('display/animations/speed_type');
        $custom = $this->getConfig('display/animations/scroll_speed_custom');
        return $speedType == 'custom' ? $custom : '"' . $speedType . '"';
    }

    /**
     * Get animation in 
     * 
     * @return string
     */
    public function getAnimationIn()
    {
        return 'animate__' . $this->getConfig('display/animations/animation_in');   
    }

    /**
     * Get animation out
     * 
     * @return string
     */
    public function getAnimationOut()
    {
        return 'animate__' . $this->getConfig('display/animations/animation_out');   
    }

    /**
     * Get position of button
     * 
     * @return string
     */
    public function getStylePosition()
    {
        switch ($this->getButtonPosition()) {
            case "0":
                return "right: " . $this->getMarginX() . "px ; bottom: " . $this->getMarginY() . "px;";
            case "1":
                return "left: " . $this->getMarginX() . "px ; bottom: " . $this->getMarginY() . "px;";
            case "2":
                return "right: " . $this->getMarginX() . "px ; top: " . $this->getMarginY() . "px;";
            case "3":
                return "left: " . $this->getMarginX() . "px ; top: " . $this->getMarginY() . "px;";
        }
    }

    /**
     * Get image type
     * 
     * @return string
     */
    public function getImageTypeDisplay()
    {
        switch ($this->getImageType()) {
            case "0":
                return $this->getImageUrl();
            case "1":
                return $this->getupload();
            case "2":
                return $this->getImageSample();
        }
    }

    /**
     * Get htlm code to show on page
     * 
     * @return string
     */
    public function getDisplayOnPage()
    {
        switch ($this->getDisplayType()) {
            case "0":
                return '<i class="' . $this->getIcon() . ' stt-icon"></i>';
            case "1":
                return '<div class="stt-text">' . __($this->getText()) . '</div>';
            case "2":
                return '<img class="stt-image" src="' . $this->getImageTypeDisplay() . '" alt="' . $this->getImageAlt() . '">';
        }
    }
    
    /**
     * Retrieve url of a view file
     *
     * @param string $fileId
     * @param array $params
     * @return string
     */
    public function getViewFileUrl($fieldId, $params = [])
    {
        try {
            $params = array_merge(['_secure' => $this->_request->isSecure()], $params);
            return $this->assetRepo->getUrlWithParams($fieldId, $params);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {}
    }

    /**
     * get where button show or hide
     *
     * @param string $controllerName
     * @param string $currentUrl
     * @param array $displayList
     * @return string
     */
    public function getButtonDisplay($display, $currentUrl, $displayList = [])
    {
        $status = false;
        switch ($display) {
            case 1:
                foreach ($displayList as $value) {
                    if ((strpos($this->controllerName, $value["url"]) !== false)
                        || (strpos($currentUrl, $value["url"]) !== false)
                        || ($value["url"] == "home" && $this->controllerName == "index")) {
                            $status = true;
                            break;
                    }
                }
                break;
            case 2:
                foreach ($displayList as $value) {
                    if ((strpos($this->controllerName, $value["url"]) !== false)
                        || (strpos($currentUrl, $value["url"]) !== false)
                        || ($value["url"] == "home" && $this->controllerName == "index")) {
                            $status = false;
                            break;
                    } else {
                        $status = true;
                    }
                }
                break;
            default:
                $status = true;
        }
        return $status;
    }
}