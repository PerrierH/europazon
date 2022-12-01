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

namespace Mageplaza\CountdownTimer\Block\Product\Configurable;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Product;
use Magento\ConfigurableProduct\Block\Product\View\Type\Configurable;
use Magento\ConfigurableProduct\Helper\Data as ConfigurableProductData;
use Magento\ConfigurableProduct\Model\ConfigurableAttributeData;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\ArrayUtils;
use Mageplaza\CountdownTimer\Helper\Data as CountdownData;
use Mageplaza\CountdownTimer\Model\Config\Source\PageView;

/**
 * Class Render
 *
 * @package Mageplaza\CountdownTimer\Block\Product\Configurable
 */
class Render extends Configurable
{
    /**
     * @var CountdownData
     */
    protected $helperData;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * Render constructor.
     *
     * @param Context $context
     * @param ArrayUtils $arrayUtils
     * @param EncoderInterface $jsonEncoder
     * @param ConfigurableProductData $helper
     * @param Product $catalogProduct
     * @param CurrentCustomer $currentCustomer
     * @param PriceCurrencyInterface $priceCurrency
     * @param ConfigurableAttributeData $configurableAttributeData
     * @param ProductRepositoryInterface $productRepository
     * @param CountdownData $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        ArrayUtils $arrayUtils,
        EncoderInterface $jsonEncoder,
        ConfigurableProductData $helper,
        Product $catalogProduct,
        CurrentCustomer $currentCustomer,
        PriceCurrencyInterface $priceCurrency,
        ConfigurableAttributeData $configurableAttributeData,
        ProductRepositoryInterface $productRepository,
        CountdownData $helperData,
        array $data = []
    ) {
        $this->productRepository = $productRepository;
        $this->helperData = $helperData;

        parent::__construct(
            $context,
            $arrayUtils,
            $jsonEncoder,
            $helper,
            $catalogProduct,
            $currentCustomer,
            $priceCurrency,
            $configurableAttributeData,
            $data
        );
    }

    /**
     * @return array|string
     */
    public function getOptionMap()
    {
        $product = $this->getProduct();
        if (!method_exists($product->getTypeInstance(), 'getConfigurableOptions')) {
            return [];
        }

        $result = array_keys($product->getTypeInstance()->getConfigurableOptions($product));

        return $this->helperData->getJsonEncode($result);
    }

    /**
     * @return mixed|string|null
     */
    public function getRuleType()
    {
        $product = $this->getProduct();
        if (!method_exists($product->getTypeInstance(), 'getConfigurableOptions')) {
            return '';
        }

        $data = $product->getTypeInstance()->getConfigurableOptions($product);
        foreach ($data as $key => $datum) {
            foreach ($datum as $item) {
                try {
                    /** @var \Magento\Catalog\Model\Product $prod */
                    $prod = $this->productRepository->get($item['sku']);
                    $rule = $this->helperData->getRuleData($prod, PageView::PRODUCT_VIEW);
                    if ($rule !== null) {
                        return $rule->getData('rule_type');
                    }
                } catch (NoSuchEntityException $e) {
                    return '';
                }
            }
        }

        return '';
    }

    /**
     * @return array|string
     */
    public function getConfigurableChildren()
    {
        $product = $this->getProduct();
        if (!method_exists($product->getTypeInstance(), 'getConfigurableOptions')) {
            return [];
        }

        $result = [];
        $page = $this->getRequest()->getFullActionName();
        $data = $product->getTypeInstance()->getConfigurableOptions($product);
        foreach ($data as $key => $datum) {
            foreach ($datum as $item) {
                $sku = $item['sku'];
                $result[$sku][$key] = $item['value_index'];

                try {
                    /** @var \Magento\Catalog\Model\Product $prod */
                    $prod = $this->productRepository->get($item['sku']);
                    $rule = $this->helperData->getRuleData($prod, PageView::PRODUCT_VIEW);
                    if ($rule !== null) {
                        $result[$sku]['countdown'] = $this->helperData->getCountdownTemplate($rule, $page);
                        $result[$sku]['style'] = $this->helperData->getJsonDecode($this->helperData->getStyleTimer($rule));
                        $result[$sku]['rule'] = $rule->getData();
                    }
                } catch (NoSuchEntityException $e) {
                    $result[$sku]['message'] = __('Requested %1 product doesn\'t exist', $sku);
                    $this->_logger->critical($e->getMessage());
                }
            }
        }

        return $this->helperData->getJsonEncode($result);
    }
}
