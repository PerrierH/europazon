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

namespace Mageplaza\CountdownTimer\Plugin\Block;

use Magento\Catalog\Block\Product\AbstractProduct as CatalogAbstractProduct;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;
use Mageplaza\CountdownTimer\Block\Widget;
use Mageplaza\CountdownTimer\Helper\Data;
use Mageplaza\CountdownTimer\Model\Config\Source\PageView;

/**
 * Class AbstractProduct
 *
 * @package Mageplaza\CountdownTimer\Plugin\Block
 */
class AbstractProduct
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * AbstractProduct constructor.
     *
     * @param Data $helperData
     */
    public function __construct(Data $helperData)
    {
        $this->helperData = $helperData;
    }

    /**
     * @param CatalogAbstractProduct $abstractProduct
     * @param callable $proceed
     * @param Product $product
     *
     * @return string
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetProductPrice(CatalogAbstractProduct $abstractProduct, callable $proceed, Product $product)
    {
        if (!$this->helperData->isEnabled()) {
            return $proceed($product);
        }

        $priceHtml = $proceed($product);

        $rule = $this->helperData->getRuleData($product, PageView::CATALOG_VIEW);
        if ($rule !== null) {
            $priceHtml .= $abstractProduct->getLayout()
                ->createBlock(Widget::class)
                ->setData([
                    'product' => $product,
                    'rule_id' => $rule->getId()
                ])
                ->toHtml();
        }

        return $priceHtml;
    }
}
