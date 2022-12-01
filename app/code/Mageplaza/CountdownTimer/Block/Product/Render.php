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

namespace Mageplaza\CountdownTimer\Block\Product;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\View\AbstractView;
use Magento\Framework\Stdlib\ArrayUtils;
use Mageplaza\CountdownTimer\Helper\Data;
use Mageplaza\CountdownTimer\Model\Config\Source\PageView;
use Mageplaza\CountdownTimer\Model\Rules;

/**
 * Class Render
 *
 * @package Mageplaza\CountdownTimer\Block\Product
 */
class Render extends AbstractView
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * Render constructor.
     *
     * @param Context $context
     * @param ArrayUtils $arrayUtils
     * @param Data $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        ArrayUtils $arrayUtils,
        Data $helperData,
        array $data = []
    ) {
        $this->helperData = $helperData;

        parent::__construct($context, $arrayUtils, $data);
    }

    /**
     * @return Rules
     */
    public function getDateByProduct()
    {
        $product = $this->getProduct();

        return $this->helperData->getRuleData($product, PageView::PRODUCT_VIEW);
    }

    /**
     * @return mixed|string
     */
    public function getStyleTimer()
    {
        $rule = $this->getDateByProduct();
        if ($rule !== null) {
            return $this->helperData->getStyleTimer($rule);
        }

        return '';
    }

    /**
     * @return string
     */
    public function getCountdownTemplate()
    {
        $rule = $this->getDateByProduct();
        if ($rule !== null) {
            return $this->helperData->getCountdownTemplate($rule, $this->getRequest()->getFullActionName());
        }

        return '';
    }
}
