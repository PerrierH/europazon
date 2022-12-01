<?php
namespace Weglio\ProductSeller\Block;

class ProductSeller extends \Magento\Framework\View\Element\Template
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
        array $data = []
    ) {
        $this->_resourceFactory = $resourceFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->product = $product;
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

    public function getProductCollection($id)
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('maas_offer_seller_id', $id);
        return $collection;
    }

    public function getAddToCartUrl($product, $additional = [])
    {
        return $this->_cartHelper->getAddUrl($product, $additional);
    }

}
