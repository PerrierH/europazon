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
 * @category    Mageplaza
 * @package     Mageplaza_AjaxLayer
 * @copyright   Copyright (c) Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\AjaxLayer\Plugin\Controller\Category;

use Mageplaza\AjaxLayer\Helper\Data as LayerData;

/**
 * Class View
 * @package Mageplaza\LayeredNavigation\Controller\Plugin\Category
 */
class View
{
    /**
     * @var LayerData
     */
    protected $_moduleHelper;

    /**
     * View constructor.
     *
     * @param LayerData $moduleHelper
     */
    public function __construct(
        LayerData $moduleHelper
    ) {
        $this->_moduleHelper = $moduleHelper;
    }

    /**
     * @param \Magento\Catalog\Controller\Category\View $action
     * @param $page
     *
     * @return mixed
     */
    public function afterExecute(\Magento\Catalog\Controller\Category\View $action, $page)
    {
        return $page;
//        if ($this->_moduleHelper->ajaxEnabled() && $action->getRequest()->isAjax() && $action->getRequest()->getParam('p')===null) {
//            $navigation = $page->getLayout()->getBlock('catalog.leftnav');
//            $products = $page->getLayout()->getBlock('category.products');
//            $result = ['products' => $products->toHtml(), 'navigation' => $navigation->toHtml()];
//            if ($this->_moduleHelper->getConfigValue('mpquickview/general/enabled')) {
//                $quickView = $page->getLayout()->getBlock('mpquickview.quickview');
//                $result['quickview'] = $quickView->toHtml();
//            }
//            $action->getResponse()->representJson(LayerData::jsonEncode($result));
//        } else {
//            return $page;
//        }
    }
}
