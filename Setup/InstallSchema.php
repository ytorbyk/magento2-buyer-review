<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */

namespace Tobai\BuyerReview\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * Installs DB schema for BuyerReview module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /* Add index on product_id field in sales_order_item table */
        $installer->getConnection()->addIndex(
            $installer->getTable('sales_order_item'),
            $setup->getIdxName($installer->getTable('sales_order_item'), ['product_id']),
            ['product_id']
        );

        $installer->endSetup();
    }
}
