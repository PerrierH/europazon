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
 * @package     ${MODULENAME}
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\CountdownTimer\Test\Unit\Helper;

use Magento\Catalog\Model\Product;
use Magento\CatalogRule\Model\CatalogRuleRepository;
use Magento\CatalogRule\Model\Rule;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Pricing\Amount\AmountInterface;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Pricing\PriceInfo\Base;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Rule\Model\Condition\Combine;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\CountdownTimer\Helper\Data;
use Mageplaza\CountdownTimer\Model\Config\Source\PageView;
use Mageplaza\CountdownTimer\Model\Config\Source\RuleType;
use Mageplaza\CountdownTimer\Model\ResourceModel\Rules as ResourceModelRules;
use Mageplaza\CountdownTimer\Model\ResourceModel\Rules\Collection;
use Mageplaza\CountdownTimer\Model\ResourceModel\Rules\CollectionFactory;
use Mageplaza\CountdownTimer\Model\Rules;
use Mageplaza\CountdownTimer\Model\RulesFactory;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class DataTest
 * @package Mageplaza\CountdownTimer\Test\Unit\Helper
 */
class DataTest extends TestCase
{
    /**
     * @var Context|PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;

    /**
     * @var ObjectManagerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManager;

    /**
     * @var StoreManagerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeManager;

    /**
     * @var CustomerSession|PHPUnit_Framework_MockObject_MockObject
     */
    protected $customerSession;

    /**
     * @var CollectionFactory|PHPUnit_Framework_MockObject_MockObject
     */
    protected $collectionFactory;

    /**
     * @var RulesFactory|PHPUnit_Framework_MockObject_MockObject
     */
    protected $rulesFactory;

    /**
     * @var ResourceModelRules|PHPUnit_Framework_MockObject_MockObject
     */
    protected $resourceModel;

    /**
     * @var CatalogRuleRepository|PHPUnit_Framework_MockObject_MockObject
     */
    protected $ruleRepository;

    /**
     * @var PriceCurrencyInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $priceCurrency;

    /**
     * @var Configurable|PHPUnit_Framework_MockObject_MockObject
     */
    protected $configurableType;

    /**
     * @var ScopeConfigInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $scopeConfig;

    /**
     * @var ManagerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $_eventManager;

    /**
     * @var TimezoneInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $localeDateMock;

    /**
     * @var Data|PHPUnit_Framework_MockObject_MockObject
     */
    private $helperData;

    protected function setUp()
    {
        $this->context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->objectManager = $this->getMockBuilder(ObjectManagerInterface::class)
            ->getMock();
        $this->storeManager = $this->getMockBuilder(StoreManagerInterface::class)
            ->getMock();
        $this->customerSession = $this->getMockBuilder(CustomerSession::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->collectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->rulesFactory = $this->getMockBuilder(RulesFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->resourceModel = $this->getMockBuilder(ResourceModelRules::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->ruleRepository = $this->getMockBuilder(CatalogRuleRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->priceCurrency = $this->getMockBuilder(PriceCurrencyInterface::class)
            ->getMock();
        $this->configurableType = $this->getMockBuilder(Configurable::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->_eventManager = $this->getMockBuilder(ManagerInterface::class)
            ->setMethods(['dispatch'])
            ->getMock();
        $this->scopeConfig = $this->getMockBuilder(ScopeConfigInterface::class)->getMock();
        $this->context->method('getScopeConfig')->willReturn($this->scopeConfig);
        $this->localeDateMock = $this->getMockBuilder(TimezoneInterface::class)->getMock();

        $this->helperData = new Data(
            $this->context,
            $this->objectManager,
            $this->storeManager,
            $this->customerSession,
            $this->collectionFactory,
            $this->rulesFactory,
            $this->resourceModel,
            $this->ruleRepository,
            $this->priceCurrency,
            $this->configurableType
        );
    }

    public function testAdminInstance()
    {
        static::assertInstanceOf(Data::class, $this->helperData);
    }

    /**
     * @param string $page
     * @param string $type
     *
     * @dataProvider getParams
     */
    public function testGetRuleData($page, $type)
    {
        $result = [
            'rule_id' => '1',
            'status' => '1',
            'store_ids' => '0',
            'customer_group_ids' => '0',
            'priority' => '0',
            'rule_type' => '2',
            'position' => '1',
            'enable_before_start' => '1',
            'enable_while_running' => '1',
            'clock_style_before' => 'style1',
            'clock_style_running' => 'style2',
            'template_before' => '{{clock}}',
            'template_running' => '{{clock}}',
            'from_date' => '2019/03/14',
            'to_date' => '2029/03/21',
            'save_amount' => '$10',
            'save_percent' => '50%'
        ];
        $count = 2;

        $store = $this->getMockBuilder(StoreInterface::class)->getMock();
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($store);
        $store->expects($this->any())
            ->method('getId')
            ->willReturn($result['store_ids']);
        $this->customerSession->method('getCustomerGroupId')->willReturn($result['customer_group_ids']);

        $rule = $this->getMockBuilder(Rules::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getRuleType',
                'getId',
                'setData',
                'getApplyProduct',
                'getCatalogRuleId',
                'getFromDate',
                'getToDate',
                'getTimeZone'
            ])
            ->getMock();
        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->setMethods(['addActiveFilter', 'addFieldToFilter', 'addPageTypeFilter', 'setOrder'])
            ->getMock();
        $priceInfor = $this->getMockBuilder(Base::class)
            ->disableOriginalConstructor()
            ->getMock();
        $priceInterface = $this->getMockBuilder(PriceInterface::class)
            ->getMock();
        $amountInterface = $this->getMockBuilder(AmountInterface::class)
            ->getMock();
        $this->localeDateMock->expects($this->any())
            ->method('getConfigTimezone')
            ->willReturn('America/Los_Angeles');

        $this->collectionFactory->expects($this->any())->method('create')->willReturn($collection);
        $collection->expects($this->once())
            ->method('addActiveFilter')
            ->with($result['customer_group_ids'], $result['store_ids'])
            ->willReturnSelf();
        $collection->expects($this->once())
            ->method('addFieldToFilter')
            ->with('rule_type', ['neq' => RuleType::NONE_PRODUCT])
            ->willReturnSelf();
        $collection->expects($this->once())
            ->method('addPageTypeFilter')
            ->with($page)
            ->willReturnSelf();
        $collection->expects($this->once())
            ->method('setOrder')
            ->with('rule_id', 'DESC')
            ->willReturn([$rule]);

        $rule->method('getRuleType')->willReturn($type);
        $rule->method('getId')->willReturn($result['rule_id']);
        $product->method('getId')->willReturn($result['rule_id']);
        $product->method('getTypeId')->willReturn('simple');
        $product->method('getPrice')->willReturn(20);
        $product->method('getSpecialPrice')->willReturn(10);
        $product->method('getSpecialFromDate')->willReturn($result['from_date']);
        $product->method('getSpecialToDate')->willReturn('2029/3/20');
        $product->expects($this->any())->method('getPriceInfo')->willReturn($priceInfor);
        if ($type === RuleType::ALL_PRODUCT_SPECIAL_PRICE || $type === RuleType::SPECIFIC_PRODUCT_SPECIAL_PRICE) {
            $priceInfor->expects($this->at(0))
                ->method('getPrice')
                ->with($this->equalTo('regular_price'))
                ->willReturn($priceInterface);
            $priceInfor->expects($this->at(1))
                ->method('getPrice')
                ->with($this->equalTo('final_price'))
                ->willReturn($priceInterface);
            $priceInterface->expects($this->at(0))->method('getAmount')->willReturn($amountInterface);
            $priceInterface->expects($this->at(1))->method('getAmount')->willReturn($amountInterface);
            $amountInterface->expects($this->at(0))->method('getValue')->willReturn(20);
            $amountInterface->expects($this->at(1))->method('getValue')->willReturn(10);
        }

        if ($type === RuleType::SPECIFIC_PRODUCT_SPECIAL_PRICE) {
            $rule->method('getApplyProduct')->with($product)->willReturn(1);
            $count++;
        }
        if ($type === RuleType::INHERIT_CATALOG_RULE) {
            $rule->method('getCatalogRuleId')->willReturn(2);
            $catalogRule = $this->getMockBuilder(Rule::class)
                ->disableOriginalConstructor()
                ->getMock();
            $combine = $this->getMockBuilder(Combine::class)
                ->disableOriginalConstructor()
                ->getMock();
            $this->ruleRepository->method('get')->with(2)->willReturn($catalogRule);
            $this->configurableType->method('getParentIdsByChild')->with(1)->willReturn(null);
            $catalogRule->method('getIsActive')->willReturn(1);
            $catalogRule->method('getConditions')->willReturn($combine);
            $combine->method('validateByEntityId')->with(1)->willReturn(true);
            $catalogRule->method('getFromDate')->willReturn($result['from_date']);
            $catalogRule->method('getToDate')->willReturn('2029/03/20');
            $catalogRule->method('getSimpleAction')->willReturn('by_percent');
            $catalogRule->method('getDiscountAmount')->willReturn(20);
            $result['save_amount'] = '';
            $result['save_percent'] = '20%';
            $count++;
        }
        if ($type === RuleType::NONE_PRODUCT) {
            $rule->method('getFromDate')->willReturn($result['from_date']);
            $rule->method('getToDate')->willReturn('2029/03/20');
            $result['save_amount'] = '';
            $result['save_percent'] = '';
            $count = 3;
            $count++;
        }

        $this->priceCurrency->method('convertAndFormat')->with(10)->willReturn($result['save_amount']);
        $this->rulesFactory->expects($this->any())->method('create')->willReturn($rule);
        $this->resourceModel->method('load')->with($rule, 1)->willReturnSelf();

        $rule->method('getTimeZone')->willReturn('America/Los_Angeles');
        $rule->expects($this->at($count++))
            ->method('setData')
            ->with('from_date', $result['from_date'])
            ->willReturnSelf();
        $rule->expects($this->at($count++))
            ->method('setData')
            ->with('to_date', $result['to_date'])
            ->willReturnSelf();
        $rule->expects($this->at($count++))
            ->method('setData')
            ->with('save_amount', $result['save_amount'])
            ->willReturnSelf();
        $rule->expects($this->at($count++))
            ->method('setData')
            ->with('save_percent', $result['save_percent'])
            ->willReturnSelf();

        static::assertEquals($rule, $this->helperData->getRuleData($product, $page));
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return [
            [PageView::PRODUCT_VIEW, RuleType::ALL_PRODUCT_SPECIAL_PRICE],
            [PageView::PRODUCT_VIEW, RuleType::SPECIFIC_PRODUCT_SPECIAL_PRICE],
            [PageView::PRODUCT_VIEW, RuleType::INHERIT_CATALOG_RULE],
            [PageView::PRODUCT_VIEW, RuleType::NONE_PRODUCT],
            [PageView::CATALOG_VIEW, RuleType::ALL_PRODUCT_SPECIAL_PRICE],
            [PageView::CATALOG_VIEW, RuleType::SPECIFIC_PRODUCT_SPECIAL_PRICE],
            [PageView::CATALOG_VIEW, RuleType::INHERIT_CATALOG_RULE],
            [PageView::CATALOG_VIEW, RuleType::NONE_PRODUCT],
        ];
    }
}
