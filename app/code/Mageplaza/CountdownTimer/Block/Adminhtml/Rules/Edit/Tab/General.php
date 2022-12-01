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

namespace Mageplaza\CountdownTimer\Block\Adminhtml\Rules\Edit\Tab;

use Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Element\Dependence;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Config\Model\Config\Source\Enabledisable;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject;
use Magento\Framework\Data\Form;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\System\Store;
use Mageplaza\CountdownTimer\Model\Config\Source\CatalogRule;
use Mageplaza\CountdownTimer\Model\Config\Source\RuleType;

/**
 * Class General
 *
 * @package Mageplaza\CountdownTimer\Block\Adminhtml\Rules\Edit\Tab
 */
class General extends Generic implements TabInterface
{
    /**
     * @var GroupRepositoryInterface
     */
    protected $_groupRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $_searchCriteriaBuilder;

    /**
     * @var DataObject
     */
    protected $_objectConverter;

    /**
     * @var Store
     */
    protected $systemStore;

    /**
     * @var Enabledisable
     */
    protected $statusOptions;

    /**
     * @var RuleType
     */
    protected $ruleType;

    /**
     * @var CatalogRule
     */
    protected $catalogRule;

    /**
     * @var DateTime
     */
    protected $_date;

    /**
     * General constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param GroupRepositoryInterface $groupRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param DataObject $dataObject
     * @param Store $systemStore
     * @param Enabledisable $statusOptions
     * @param RuleType $ruleType
     * @param CatalogRule $catalogRule
     * @param DateTime $dateTime
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        GroupRepositoryInterface $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        DataObject $dataObject,
        Store $systemStore,
        Enabledisable $statusOptions,
        RuleType $ruleType,
        CatalogRule $catalogRule,
        DateTime $dateTime,
        array $data = []
    ) {
        $this->_groupRepository = $groupRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_objectConverter = $dataObject;
        $this->systemStore = $systemStore;
        $this->statusOptions = $statusOptions;
        $this->ruleType = $ruleType;
        $this->catalogRule = $catalogRule;
        $this->_date = $dateTime;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return Generic
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('mpcountdowntimer_rules');

        /** @var Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('mpcountdowntimer_');
        $form->setFieldNameSuffix('mpcountdowntimer');
        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('Item Information'),
            'class' => 'fieldset-wide'
        ]);
        if ($model->getId()) {
            $fieldset->addField('rule_id', 'hidden', ['name' => 'rule_id']);
        }

        $fieldset->addField('name', 'text', [
            'name' => 'name',
            'label' => __('Name'),
            'title' => __('Name'),
            'required' => true
        ]);

        $fieldset->addField('status', 'select', [
            'name' => 'status',
            'label' => __('Status'),
            'title' => __('Status'),
            'values' => $this->statusOptions->toOptionArray()
        ]);
        if (!$model->getId()) {
            $model->setData('status', 1);
        }

        if ($this->_storeManager->isSingleStoreMode()) {
            $fieldset->addField('store_ids', 'hidden', [
                'name' => 'store_ids',
                'value' => $this->_storeManager->getStore()->getId()
            ]);
        } else {
            /** @var RendererInterface $rendererBlock */
            $rendererBlock = $this->getLayout()->createBlock(Element::class);
            $fieldset->addField('store_ids', 'multiselect', [
                'name' => 'store_ids',
                'label' => __('Store Views'),
                'title' => __('Store Views'),
                'required' => true,
                'values' => $this->systemStore->getStoreValuesForForm(false, true)
            ])->setRenderer($rendererBlock);
        }
        if (!$model->hasData('store_ids')) {
            $model->setStoreIds(0);
        }

        $customerGroups = $this->_groupRepository->getList($this->_searchCriteriaBuilder->create())->getItems();
        $fieldset->addField('customer_group_ids', 'multiselect', [
            'name' => 'customer_group_ids[]',
            'label' => __('Customer Groups'),
            'title' => __('Customer Groups'),
            'required' => true,
            'values' => $this->_objectConverter->toOptionArray($customerGroups, 'id', 'code'),
            'note' => __('Select customer group(s) to display the rule to')
        ]);

        $ruleType = $fieldset->addField('rule_type', 'select', [
            'name' => 'rule_type',
            'label' => __('Apply for'),
            'title' => __('Apply for'),
            'values' => $this->ruleType->toOptionArray()
        ]);

        $catalogRule = $fieldset->addField('catalog_rule_id', 'select', [
            'name' => 'catalog_rule_id',
            'label' => __('Select Catalog Price Rule'),
            'title' => __('Select Catalog Price Rule'),
            'values' => $this->catalogRule->toOptionArray()
        ]);

        $fromDate = $fieldset->addField('from_date', 'date', [
            'name' => 'from_date',
            'label' => __('Start Countdown Date'),
            'title' => __('Start Countdown Date'),
            'class' => 'validate-date validate-date-range date-range-task_data-from',
            'input_format' => \Magento\Framework\Stdlib\DateTime::DATETIME_INTERNAL_FORMAT,
            'date_format' => 'M/d/yyyy',
            'time_format' => 'HH:mm',
            'timezone' => false
        ]);

        $toDate = $fieldset->addField('to_date', 'date', [
            'name' => 'to_date',
            'label' => __('End Countdown Date'),
            'title' => __('End Countdown Date'),
            'class' => 'validate-date validate-date-range date-range-task_data-to',
            'input_format' => \Magento\Framework\Stdlib\DateTime::DATETIME_INTERNAL_FORMAT,
            'date_format' => 'M/d/yyyy',
            'time_format' => 'HH:mm',
            'timezone' => false
        ]);

        $fieldset->addField('priority', 'text', [
            'name' => 'priority',
            'label' => __('Priority'),
            'class' => 'validate-digits',
            'note' => __('Default is 0. The rule with the lower number will get the higher priority.')
        ]);

        /** @var Dependence $dependencies */
        $dependencies = $this->getLayout()->createBlock(Dependence::class);
        $dependencies->addFieldMap($ruleType->getHtmlId(), $ruleType->getName())
            ->addFieldMap($catalogRule->getHtmlId(), $catalogRule->getName())
            ->addFieldMap($fromDate->getHtmlId(), $fromDate->getName())
            ->addFieldMap($toDate->getHtmlId(), $toDate->getName())
            ->addFieldDependence($catalogRule->getName(), $ruleType->getName(), RuleType::INHERIT_CATALOG_RULE)
            ->addFieldDependence($fromDate->getName(), $ruleType->getName(), RuleType::NONE_PRODUCT)
            ->addFieldDependence($toDate->getName(), $ruleType->getName(), RuleType::NONE_PRODUCT);
        // define field dependencies
        $this->setChild('form_after', $dependencies);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('General');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}
