<?php
/**
 * Create table 'webinseofflinestores/offlinestores_product' for represent relations between offline stores and products
 */
$installer = $this;
$offlineStoresProductTable = 'webinseofflinestores/offlinestores_product';
$tableName = $installer->getTable($offlineStoresProductTable);
$installer->getConnection()->dropTable($tableName);

$table = $installer->getConnection()
    ->newTable($tableName)
    ->addColumn('offlinestore_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        'default'   => '0',
    ), 'Category ID')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        'default'   => '0',
    ), 'Product ID')
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'default'   => '0',
    ), 'Position')
    ->addIndex($installer->getIdxName($offlineStoresProductTable, array('product_id')),
        array('product_id'))
    ->addForeignKey($installer->getFkName($offlineStoresProductTable, 'offlinestore_id', 'webinseofflinestores/table_offlinestores', 'entity_id'),
        'offlinestore_id', $installer->getTable('webinseofflinestores/table_offlinestores'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName($offlineStoresProductTable, 'product_id', 'catalog/product', 'entity_id'),
        'product_id', $installer->getTable('catalog/product'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Product To Offline Store Linkage Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();