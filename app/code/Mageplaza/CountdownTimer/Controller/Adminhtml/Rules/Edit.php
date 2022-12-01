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

namespace Mageplaza\CountdownTimer\Controller\Adminhtml\Rules;

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Mageplaza\CountdownTimer\Controller\Adminhtml\Rules;

/**
 * Class Edit
 *
 * @package Mageplaza\CountdownTimer\Controller\Adminhtml\Rules
 */
class Edit extends Rules
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Mageplaza_CountdownTimer::edit';

    /**
     * @return Page|ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $ruleId = $this->getRequest()->getParam('rule_id');
        /** @var \Mageplaza\CountdownTimer\Model\Rules $model */
        $model = $this->rulesFactory->create();
        if ($ruleId) {
            $this->resourceModel->load($model, $ruleId);
            if ($model->getId() !== $ruleId) {
                $this->messageManager->addErrorMessage(__('This rule no longer exists.'));

                return $this->_redirect('*/*');
            }
        }

        // set entered data if was error when we do save
        $data = $this->_session->getData('mpcountdowntimer_rules_data', true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->coreRegistry->register('mpcountdowntimer_rules', $model);

        /** @var Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->getConfig()->getTitle()->prepend($ruleId ? $model->getName() : __('New Countdown Timer'));

        return $resultPage;
    }
}
