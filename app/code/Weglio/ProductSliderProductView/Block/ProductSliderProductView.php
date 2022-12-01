<?php
namespace Weglio\ProductSliderProductView\Block;

class ProductSliderProductView extends \Magento\Framework\View\Element\Template
{
    protected $_resourceFactory;
    protected $_productCollectionFactory;
    protected $_imageHelper;
    protected $_cartHelper;
    protected $product;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Reports\Model\ResourceModel\Report\Collection\Factory $resourceFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product $product,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_resourceFactory = $resourceFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->product = $product;
        $this->_registry = $registry;
        $this->_imageHelper = $context->getImageHelper();
        $this->_cartHelper = $context->getCartHelper();
        parent::__construct($context, $data);
    }


    public function imageHelperObj()
    {
        return $this->_imageHelper;
    }

    public function getProduct($id)
    {
        return $this->product->load($id);
    }

    public function getProductCollectionByCategories($category)
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoriesFilter(['in' => $category]);
        return $collection;
    }

    public function getAddToCartUrl($product, $additional = [])
    {
        return $this->_cartHelper->getAddUrl($product, $additional);
    }

    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }


}
