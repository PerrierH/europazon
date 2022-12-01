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
 * @package     Mageplaza_ProductLabels
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ProductLabels\Console;

use Exception;
use Magento\Framework\Console\Cli;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ApplyRule
 * @package Mageplaza\SeoRule\Console
 */
class ApplyRule extends Command
{
    /**
     * Name of input option
     */
    const INPUT_KEY_RULE_ID = 'id';

    /**
     * @var \Mageplaza\ProductLabels\Model\RuleFactory
     */
    protected $ruleFactory;

    /**
     * @var \Mageplaza\ProductLabels\Model\MetaFactory
     */
    protected $metaFactory;

    /**
     * @var \Mageplaza\ProductLabels\Helper\HelperConsole
     */
    protected $helperData;

    /**
     * @var \Mageplaza\ProductLabels\Model\Indexer\RuleIndexer
     */
    protected $ruleIndexer;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var State
     */
    protected $state;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param State $state
     * @param string|null $name
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        State $state,
        string $name = null
    ) {
        $this->objectManager = $objectManager;
        $this->state         = $state;

        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $options = [
            new InputOption(
                self::INPUT_KEY_RULE_ID,
                null,
                InputOption::VALUE_REQUIRED,
                'Apply rule id'
            )
        ];
        $this->setName('mpproductlabels:applyrule');
        $this->setDescription(__('Apply rule.'));
        $this->setDefinition($options);

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->emulateAreaCode(
            'frontend',
            function () use ($input, $output) {
                $this->helperData = $this->objectManager->get(\Mageplaza\ProductLabels\Helper\HelperConsole::class);

                $this->ruleFactory = $this->helperData->getRuleModel();
                $this->metaFactory = $this->helperData->getMetaModel();
                $this->ruleIndexer = $this->helperData->getRuleIndexerModel();

                if (!$this->helperData->isEnabled()) {
                    $output->writeln('<error>Command cannot run because the module is disabled.</error>');

                    return Cli::RETURN_FAILURE;
                }

                if ($id = $input->getOption(self::INPUT_KEY_RULE_ID)) {
                    /** @var \Mageplaza\ProductLabels\Model\Rule $ruleModel */
                    $ruleModel = $this->ruleFactory->create()->load($id);
                    $metaModel = $this->metaFactory->create();

                    if (!$ruleModel->getId()) {
                        $output->writeln('Not found. Please check this rule again!');

                        return Cli::RETURN_FAILURE;
                    }

                    try {
                        $this->helperData->applyRuleId($ruleModel, $metaModel);
                        $output->writeln('Rule id has been applied.');

                        return Cli::RETURN_SUCCESS;
                    } catch (Exception $e) {
                        $output->writeln('Cannot apply rule!');

                        return Cli::RETURN_FAILURE;
                    }
                } else {
                    $this->ruleIndexer->executeFull();
                    $output->writeln('The rule has been applied.');

                    return Cli::RETURN_SUCCESS;
                }

            }
        );
    }
}
