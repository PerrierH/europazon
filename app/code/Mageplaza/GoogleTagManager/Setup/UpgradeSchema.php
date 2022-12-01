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
 * @package     Mageplaza_GoogleTagManager
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\GoogleTagManager\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Zend_Db_Exception;

/**
 * Class UpgradeSchema
 * @package Mageplaza\GoogleTagManager\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @throws Zend_Db_Exception
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer  = $setup;
        $connection = $installer->getConnection();
        $installer->startSetup();

        if (version_compare($context->getVersion(), '2.0.1', '<')) {
            if (!$installer->tableExists('mageplaza_gtm_export_template')) {
                $table = $installer->getConnection()
                    ->newTable($installer->getTable('mageplaza_gtm_export_template'))
                    ->addColumn('id', Table::TYPE_INTEGER, null, [
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true
                    ], 'Id')
                    ->addColumn('name', Table::TYPE_TEXT, '256', [], 'Name')
                    ->addColumn('template', Table::TYPE_TEXT, '2M', [], 'Template')
                    ->setComment('Google Tag Manager Template');

                $connection->createTable($table);
            }
        }

        $setup->endSetup();
    }
}
