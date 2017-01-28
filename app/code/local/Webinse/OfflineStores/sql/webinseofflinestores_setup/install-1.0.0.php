<?php
$installer = $this;
var_dump(get_class($installer));die;
$tableOfflineStores = $installer->getTable('webinseofflinestores/webinse_offlinestores');

/**
 * Create table array('webinseofflinestores/webinse_offlinestores')
 */
$table = $installer->getConnection()
    ->newTable($tableOfflineStores)
    ->addColumn('offlinestores_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Offline Store ID')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Entity Type ID')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Attribute ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Store ID')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Entity ID')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Value')
    ->addIndex(
        $installer->getIdxName(
            array('catalog/category', 'varchar'),
            array('entity_type_id', 'entity_id', 'attribute_id', 'store_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('entity_type_id', 'entity_id', 'attribute_id', 'store_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName(array('catalog/category', 'varchar'), array('entity_id')),
        array('entity_id'))
    ->addIndex($installer->getIdxName(array('catalog/category', 'varchar'), array('attribute_id')),
        array('attribute_id'))
    ->addIndex($installer->getIdxName(array('catalog/category', 'varchar'), array('store_id')),
        array('store_id'))
    ->addForeignKey(
        $installer->getFkName(array('catalog/category', 'varchar'), 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(array('catalog/category', 'varchar'), 'entity_id', 'catalog/category', 'entity_id'),
        'entity_id', $installer->getTable('catalog/category'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(array('catalog/category', 'varchar'), 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Catalog Category Varchar Attribute Backend Table');
$installer->getConnection()->createTable($table);

die;
/*$installer->startSetup();
$installer->getConnection()->dropTable($tableNews);

$table = $installer->getConnection()->newTable($tableNews)
->addColumn('offlinestore_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'identity'  => true,
    'nullable'  => false,
    'primary'   => true,
))->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
    'nullable'  => false,
))->addColumn('image', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
    'nullable'  => false,
))->addColumn('short_description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
    'nullable'  => false,
))->addColumn('created', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
    'nullable'  => false,
));
$installer->getConnection()->createTable($table);

$installer->endSetup();*/


/**
 * Create table 'catalog/category_product'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('webinseofflinestores/webinse_offlinestores_product'))
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
        'nullable'  => false,
        'default'   => '0',
    ), 'Position')
    /*    ->addIndex($installer->getIdxName('catalog/category_product', array('category_id')),
            array('category_id'))*/
    ->addIndex($installer->getIdxName('catalog/category_product', array('product_id')),
        array('product_id'))
    ->addForeignKey($installer->getFkName('catalog/category_product', 'product_id', 'webinseofflinestores/webinse_offlinestores', 'offlinestore_id'),
        'offlinestore_id', $installer->getTable('webinseofflinestores/webinse_offlinestores'), 'offlinestore_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('catalog/category_product', 'product_id', 'catalog/product', 'entity_id'),
        'product_id', $installer->getTable('catalog/product'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Catalog Product To Category Linkage Table');
$installer->getConnection()->createTable($table);