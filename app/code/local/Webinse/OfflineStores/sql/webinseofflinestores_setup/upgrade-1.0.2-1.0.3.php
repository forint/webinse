<?php
$installer = $this;
/**
 * Create table 'webinseofflinestores/offlinestore_product_index'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('webinseofflinestores/offlinestore_product_index'))
    ->addColumn('offlinestore_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        'default'   => '0',
    ), 'Offline Store ID')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        'default'   => '0',
    ), 'Product ID')
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
    ), 'Position')
    ->addColumn('is_parent', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is Parent')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        'default'   => '0',
    ), 'Store ID')
    ->addColumn('visibility', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Visibility')
    ->addIndex(
        $installer->getIdxName(
            'webinseofflinestores/offlinestore_product_index',
            array('product_id', 'store_id', 'offlinestore_id', 'visibility')
        ),
        array('product_id', 'store_id', 'offlinestore_id', 'visibility'))
    ->addIndex(
        $installer->getIdxName(
            'webinseofflinestores/offlinestore_product_index',
            array('store_id', 'offlinestore_id', 'visibility', 'is_parent', 'position')
        ),
        array('store_id', 'offlinestore_id', 'visibility', 'is_parent', 'position'))
    ->addForeignKey(
        $installer->getFkName('webinseofflinestores/offlinestore_product_index', 'offlinestore_id', 'webinseofflinestores/table_offlinestores', 'entity_id'),
        'offlinestore_id', $installer->getTable('webinseofflinestores/table_offlinestores'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName('webinseofflinestores/offlinestore_product_index', 'product_id', 'catalog/product', 'entity_id'),
        'product_id', $installer->getTable('catalog/product'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName('webinseofflinestores/offlinestore_product_index', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Catalog Offline Store Product Index');
$installer->getConnection()->createTable($table);

$installer->endSetup();