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

namespace Mageplaza\CountdownTimer\Ui\Component\Listing\Column;

use Magento\Customer\Model\ResourceModel\Group\Collection as GroupCollection;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class CustomerGroups
 *
 * @package Mageplaza\CountdownTimer\Ui\Component\Listing\Column
 */
class CustomerGroups extends Column
{
    /**
     * @var GroupCollection
     */
    protected $customerGroup;

    /**
     * CustomerGroup constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param GroupCollection $GroupCollection
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        GroupCollection $GroupCollection,
        array $components = [],
        array $data = []
    ) {
        $this->customerGroup = $GroupCollection;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item[$this->getData('name')])) {
                    $item[$this->getData('name')] = $this->prepareItem($item);
                }
            }
        }

        return $dataSource;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    public function prepareItem(array $item)
    {
        $content = [];
        $item[$this->getData('name')] = explode(',', $item[$this->getData('name')]);
        $origGroup = $item['customer_group_ids'];

        if (!is_array($origGroup)) {
            $origGroup = [$origGroup];
        }

        $customer = $this->customerGroup->toOptionHash();
        foreach ($origGroup as $group) {
            if (isset($customer[$group])) {
                $content[] = $customer[$group];
            }
        }
        if (count($content) === $this->customerGroup->count()) {
            return __('All Customer Groups');
        }

        return implode(', ', $content);
    }
}
