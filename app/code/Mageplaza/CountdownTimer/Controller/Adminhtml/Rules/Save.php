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

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\DateTime;
use Magento\Framework\View\Result\PageFactory;
use Mageplaza\CountdownTimer\Controller\Adminhtml\Rules;
use Mageplaza\CountdownTimer\Helper\Data;
use Mageplaza\CountdownTimer\Model\Config\Source\RuleType;
use Mageplaza\CountdownTimer\Model\ResourceModel\Rules as ResourceModelRules;
use Mageplaza\CountdownTimer\Model\RulesFactory;
use Psr\Log\LoggerInterface;
use Zend_Filter_Input;

/**
 * Class Save
 *
 * @package Mageplaza\CountdownTimer\Controller\Adminhtml\Rules
 */
class Save extends Rules
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $coreRegistry
     * @param RulesFactory $rulesFactory
     * @param ResourceModelRules $resourceModel
     * @param DateTime $date
     * @param Data $helperData
     * @param LoggerInterface $logger
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        RulesFactory $rulesFactory,
        ResourceModelRules $resourceModel,
        DateTime $date,
        Data $helperData,
        LoggerInterface $logger,
        DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;

        parent::__construct(
            $context,
            $resultPageFactory,
            $coreRegistry,
            $rulesFactory,
            $resourceModel,
            $date,
            $helperData,
            $logger
        );
    }

    /**
     * @return ResponseInterface|ResultInterface|void
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue('mpcountdowntimer');
        if ($data) {
            $ruleId = $this->getRequest()->getParam('rule_id');

            try {
                /** @var $model \Mageplaza\CountdownTimer\Model\Rules */
                $model = $this->rulesFactory->create();

                $filterValues = [
                    'from_date' => $this->_dateFilter,
                    'to_date' => $this->_dateFilter,
                ];
                $inputFilter = new Zend_Filter_Input($filterValues, [], $data);
                $data = $inputFilter->getUnescaped();

                if ($ruleId) {
                    $this->resourceModel->load($model, $ruleId);
                    if ($ruleId !== $model->getId()) {
                        throw new LocalizedException(__('The wrong rule is specified.'));
                    }
                }

                $validateResult = $model->validateData(new DataObject($data));
                if ($validateResult !== true) {
                    foreach ($validateResult as $errorMessage) {
                        $this->messageManager->addErrorMessage($errorMessage);
                    }
                    $this->_session->setPageData($data);
                    $this->dataPersistor->set('mpcountdowntimer_rules', $data);
                    $this->_redirect('*/*/edit', ['rule_id' => $model->getId()]);

                    return;
                }
                $rule = $this->getRequest()->getPostValue('rule');
                if ($rule) {
                    if (isset($rule['conditions']) && $data['rule_type'] === RuleType::SPECIFIC_PRODUCT_SPECIAL_PRICE) {
                        $data['conditions'] = $rule['conditions'];
                    }
                    unset($rule);
                }
                if ($data['enable_before_start']) {
                    $data['css_before'] = $this->helperData->getJsonEncode(
                        [
                            'title_color_before' => $data['title_color_before'],
                            'message_color_before' => $data['message_color_before'],
                            'clock_color_before' => $data['clock_color_before'],
                            'number_color_before' => $data['number_color_before'],
                            'text_color_before' => $data['text_color_before']
                        ]
                    );
                }
                if ($data['enable_while_running']) {
                    $data['css_running'] = $this->helperData->getJsonEncode(
                        [
                            'title_color_running' => $data['title_color_running'],
                            'message_color_running' => $data['message_color_running'],
                            'clock_color_running' => $data['clock_color_running'],
                            'number_color_running' => $data['number_color_running'],
                            'text_color_running' => $data['text_color_running']
                        ]
                    );
                }

                $model->loadPost($data);
                $this->_session->setPageData($data);
                $this->dataPersistor->set('mpcountdowntimer_rules', $data);

                $this->resourceModel->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the rule.'));
                $this->_session->setPageData(false);
                $this->dataPersistor->clear('mpcountdowntimer_rules');

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['rule_id' => $model->getId()]);

                    return;
                }
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Something went wrong while saving the rule data. Please review the error log.')
                );
                $this->logger->critical($e->getMessage());
                $this->_session->setPageData($data);
                $this->dataPersistor->set('mpcountdowntimer_rules', $data);
                if (empty($ruleId)) {
                    $this->_redirect('*/*/new');
                } else {
                    $this->_redirect('*/*/edit', ['rule_id' => $ruleId]);
                }

                return;
            }
        }
        $this->_redirect('*/*/');
    }
}
