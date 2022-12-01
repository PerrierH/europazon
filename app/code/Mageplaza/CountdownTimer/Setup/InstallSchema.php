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

namespace Mageplaza\CountdownTimer\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Zend_Db_Exception;

/**
 * Class InstallSchema
 *
 * @package Mageplaza\CountdownTimer\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @throws Zend_Db_Exception
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (!$installer->tableExists('mageplaza_countdown_timer_rules')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mageplaza_countdown_timer_rules'))
                ->addColumn('rule_id', Table::TYPE_INTEGER, null, [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ], 'Rule Id')
                ->addColumn('name', Table::TYPE_TEXT, 255, [], 'Name')
                ->addColumn('status', Table::TYPE_INTEGER, 1, ['nullable' => false], 'Status')
                ->addColumn('store_ids', Table::TYPE_TEXT, 255, ['nullable' => false], 'Store')
                ->addColumn('customer_group_ids', Table::TYPE_TEXT, 255, ['nullable' => false], 'Customer Group')
                ->addColumn('priority', Table::TYPE_INTEGER, null, ['nullable' => false], 'Priority')
                ->addColumn('conditions_serialized', Table::TYPE_TEXT, '2M', [], 'Conditions Serialized')
                ->addColumn('rule_type', Table::TYPE_TEXT, 64, [], 'Rule Type')
                ->addColumn('catalog_rule_id', Table::TYPE_TEXT, 64, [], 'Catalog Price Rule Id')
                ->addColumn('from_date', Table::TYPE_DATETIME, null, [], 'From Date')
                ->addColumn('to_date', Table::TYPE_DATETIME, null, [], 'To Date')
                ->addColumn('position', Table::TYPE_TEXT, 64, [], 'Position')
                ->addColumn('enable_before_start', Table::TYPE_INTEGER, 1, ['nullable' => false], 'Enable before')
                ->addColumn('enable_while_running', Table::TYPE_INTEGER, 1, ['nullable' => false], 'Enable running')
                ->addColumn('clock_style_before', Table::TYPE_TEXT, 64, [], 'Clock style')
                ->addColumn('clock_style_running', Table::TYPE_TEXT, 64, [], 'Clock style')
                ->addColumn('template_before_product', Table::TYPE_TEXT, '2M', [], 'Template before on product')
                ->addColumn('template_before_category', Table::TYPE_TEXT, '2M', [], 'Template before on category')
                ->addColumn('template_running_product', Table::TYPE_TEXT, '2M', [], 'Template running on product')
                ->addColumn('template_running_category', Table::TYPE_TEXT, '2M', [], 'Template running on category')
                ->addColumn('css_before', Table::TYPE_TEXT, '2M', [], 'CSS before')
                ->addColumn('css_running', Table::TYPE_TEXT, '2M', [], 'CSS running')
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Creation Time'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                    'Update Time'
                )
                ->addIndex(
                    $setup->getIdxName(
                        $installer->getTable('mageplaza_countdown_timer_rules'),
                        ['name'],
                        AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['name'],
                    ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
                );
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
