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

namespace Mageplaza\CountdownTimer\Api;

/**
 * Interface RuleRepositoryInterface
 * @package Mageplaza\CountdownTimer\Api
 */
interface RuleRepositoryInterface
{
    /**
     * @param int $id
     *
     * @return \Mageplaza\CountdownTimer\Api\Data\RuleInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($id);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria The search criteria.
     *
     * @return \Mageplaza\CountdownTimer\Api\Data\RuleSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null);

    /**
     * @param int $id
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    public function delete($id);

    /**
     * @param \Mageplaza\CountdownTimer\Api\Data\RuleInterface $rule
     *
     * @return \Mageplaza\CountdownTimer\Api\Data\RuleInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\InputException
     */
    public function save($rule);

    /**
     * @param int $customerId
     * @param string $type
     * @param int $id
     *
     * @return \Mageplaza\CountdownTimer\Api\Data\RuleInterface|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByProduct($customerId, $type, $id);
}
