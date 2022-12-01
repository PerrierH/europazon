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

namespace Mageplaza\CountdownTimer\Model\Api;

use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Model\Group;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Customer\Model\ResourceModel\Group\Collection;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Mageplaza\CountdownTimer\Api\Data\RuleInterface;
use Mageplaza\CountdownTimer\Api\RuleRepositoryInterface;
use Mageplaza\CountdownTimer\Helper\Data;
use Mageplaza\CountdownTimer\Model\Config\Source\ClockStyle;
use Mageplaza\CountdownTimer\Model\Config\Source\RuleType;
use Mageplaza\CountdownTimer\Model\ResourceModel\Rules as RuleResource;
use Mageplaza\CountdownTimer\Model\ResourceModel\Rules\CollectionFactory;
use Mageplaza\CountdownTimer\Model\RulesFactory;

/**
 * Class RuleRepository
 * @package Mageplaza\CountdownTimer\Model\Api
 */
class RuleRepository implements RuleRepositoryInterface
{
    /**
     * @var Data
     */
    private $helperData;

    /**
     * @var RulesFactory
     */
    private $ruleFactory;

    /**
     * @var RuleResource
     */
    private $ruleResource;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var SearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var RuleType
     */
    private $ruleTypeConfig;

    /**
     * @var ClockStyle
     */
    private $clockStyleConfig;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var Collection
     */
    private $customerGroupCollection;

    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * RuleRepository constructor.
     *
     * @param Data $helperData
     * @param RulesFactory $ruleFactory
     * @param RuleResource $ruleResource
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param RuleType $ruleTypeConfig
     * @param ClockStyle $clockStyleConfig
     * @param ProductRepository $productRepository
     * @param Collection $customerGroupCollection
     * @param CustomerRepository $customerRepository
     */
    public function __construct(
        Data $helperData,
        RulesFactory $ruleFactory,
        RuleResource $ruleResource,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsInterfaceFactory $searchResultsFactory,
        RuleType $ruleTypeConfig,
        ClockStyle $clockStyleConfig,
        ProductRepository $productRepository,
        Collection $customerGroupCollection,
        CustomerRepository $customerRepository
    ) {
        $this->helperData = $helperData;
        $this->ruleFactory = $ruleFactory;
        $this->ruleResource = $ruleResource;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->ruleTypeConfig = $ruleTypeConfig;
        $this->clockStyleConfig = $clockStyleConfig;
        $this->productRepository = $productRepository;
        $this->customerGroupCollection = $customerGroupCollection;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @throws LocalizedException
     */
    public function checkEnable()
    {
        if (!$this->helperData->isEnabled()) {
            throw new LocalizedException(__('The module is disabled'));
        }
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        $this->checkEnable();

        $rule = $this->ruleFactory->create();
        $this->ruleResource->load($rule, $id);

        if (!$rule->getId()) {
            throw new LocalizedException(__('Rule does not exits'));
        }

        return $rule;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria = null)
    {
        $this->checkEnable();

        if ($searchCriteria === null) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        }

        $collection = $this->collectionFactory->create();

        foreach ($collection->getItems() as $item) {
            $item->afterLoad();
        }

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var SearchResultsInterface $searchResult */
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }

    /**
     * @inheritDoc
     */
    public function delete($id)
    {
        $this->checkEnable();

        $rule = $this->ruleFactory->create();
        $this->ruleResource->load($rule, $id);

        if (!$rule->getId()) {
            throw new LocalizedException(__('Rule does not exits'));
        }

        $this->ruleResource->delete($rule);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function save($rule)
    {
        $this->checkEnable();

        $ruleId = $rule->getRuleId();

        $ruleModel = $this->ruleFactory->create();

        if ($ruleId) {
            $this->ruleResource->load($ruleModel, $ruleId);

            if (!$ruleModel->getId()) {
                throw new NoSuchEntityException(__('No such entity ID'));
            }
        }

        $modelData = $ruleModel->getData();
        $data = $rule->getData();
        $mergedData = array_merge($modelData, $data);
        $this->validateData($mergedData);
        $ruleModel->addData($mergedData);

        $this->ruleResource->save($ruleModel);
        $this->ruleResource->load($ruleModel, $ruleModel->getId());

        return $ruleModel;
    }

    /**
     * @param array $data
     *
     * @throws InputException
     */
    private function validateData(&$data)
    {
        $requiredFields = [
            RuleInterface::NAME,
            RuleInterface::STATUS,
            RuleInterface::STORE_IDS,
            RuleInterface::CUSTOMER_GROUP_IDS,
            RuleInterface::RULE_TYPE,
            RuleInterface::ENABLE_BEFORE_START,
            RuleInterface::ENABLE_WHILE_RUNNING,
        ];

        if ($data[RuleInterface::ENABLE_BEFORE_START]) {
            $requiredFields[] = RuleInterface::CLOCK_STYLE_BEFORE;
            $requiredFields[] = RuleInterface::TEMPLATE_BEFORE_PRODUCT;
            $requiredFields[] = RuleInterface::TEMPLATE_BEFORE_CATEGORY;
        }

        if ($data[RuleInterface::ENABLE_WHILE_RUNNING]) {
            $requiredFields[] = RuleInterface::CLOCK_STYLE_RUNNING;
            $requiredFields[] = RuleInterface::TEMPLATE_RUNNING_PRODUCT;
            $requiredFields[] = RuleInterface::TEMPLATE_RUNNING_CATEGORY;
        }

        $missingFields = [];
        foreach ($requiredFields as $requiredField) {
            if (!isset($data[$requiredField]) || $data[$requiredField] === null
                || (is_string($data[$requiredField]) && trim($data[$requiredField]) === '')) {
                $missingFields[] = $requiredField;
            }
        }

        if (!empty($missingFields)) {
            throw new InputException(__('Please specific field(s): %1', implode(',', $missingFields)));
        }

        $validRuleType = array_keys($this->ruleTypeConfig->toArray());
        if (isset($data[RuleInterface::RULE_TYPE])
            && !in_array((int)$data[RuleInterface::RULE_TYPE], $validRuleType, true)
        ) {
            throw new InputException(__(
                'Please specific %1 field. Valid type must be one of values: %2',
                RuleInterface::RULE_TYPE,
                implode(',', $validRuleType)
            ));
        }

        $validClockStyle = array_keys($this->clockStyleConfig->toArray());

        if (isset($data[RuleInterface::CLOCK_STYLE_BEFORE])
            && !in_array($data[RuleInterface::CLOCK_STYLE_BEFORE], $validClockStyle, true)
        ) {
            throw new InputException(__(
                'Please specific %1 field. Valid style must be one of values: %2',
                RuleInterface::CLOCK_STYLE_BEFORE,
                implode(',', $validClockStyle)
            ));
        }

        if (isset($data[RuleInterface::CLOCK_STYLE_RUNNING])
            && !in_array($data[RuleInterface::CLOCK_STYLE_RUNNING], $validClockStyle, true)
        ) {
            throw new InputException(__(
                'Please specific %1 field. Valid style must be one of values: %2',
                RuleInterface::CLOCK_STYLE_RUNNING,
                implode(',', $validClockStyle)
            ));
        }

        if (isset($data[RuleInterface::PRIORITY])) {
            if ($data[RuleInterface::PRIORITY] < 0) {
                throw new InputException(__('Priority is not negative number'));
            }
            $data[RuleInterface::PRIORITY] = (int)$data[RuleInterface::PRIORITY];
        }

        $storeIds = explode(',', $data[RuleInterface::STORE_IDS]);
        $validStoreIds = array_keys($this->helperData->getStoreManager()->getStores());
        $validStoreIds[] = 0;

        $invalidStore = array_diff($storeIds, $validStoreIds);
        if (!empty($invalidStore)) {
            throw new InputException(__(
                'Please specific %1 field. Valid store must be one of values: %2',
                RuleInterface::STORE_IDS,
                implode(',', $validStoreIds)
            ));
        }

        $customerGroupIds = explode(',', $data[RuleInterface::CUSTOMER_GROUP_IDS]);
        $validGroupIds = array_keys($this->customerGroupCollection->getAllIds());

        $invalidGroups = array_diff($customerGroupIds, $validGroupIds);
        if (!empty($invalidGroups)) {
            throw new InputException(__(
                'Please specific %1 field. Valid group must be one of values: %2',
                RuleInterface::CUSTOMER_GROUP_IDS,
                implode(',', $validGroupIds)
            ));
        }

        $data[RuleInterface::STATUS] = (bool)$data[RuleInterface::STATUS];
        $data[RuleInterface::ENABLE_BEFORE_START] = (bool)$data[RuleInterface::ENABLE_BEFORE_START];
        $data[RuleInterface::ENABLE_WHILE_RUNNING] = (bool)$data[RuleInterface::ENABLE_WHILE_RUNNING];
    }

    /**
     * @inheritDoc
     */
    public function getByProduct($customerId, $type, $id)
    {
        $product = $this->productRepository->getById($id);
        if ($customerId) {
            $customer = $this->customerRepository->getById($customerId);
            $customerGroupId = $customer->getGroupId();
        } else {
            $customerGroupId = Group::NOT_LOGGED_IN_ID;
        }

        $this->helperData->setCustomerGroupId($customerGroupId);

        return $this->helperData->getRuleData($product, $type);
    }
}
