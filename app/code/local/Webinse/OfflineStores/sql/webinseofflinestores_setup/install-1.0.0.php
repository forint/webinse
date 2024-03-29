<?php
$installer = $this;
$tableOfflineStores = 'webinseofflinestores/table_offlinestores';
$tableAdditionalAttribute = 'webinseofflinestores/attribute_additional';
$tableEavAttributeWebsite = 'webinseofflinestores/attribute_website';

/**
 * Drop Offline Stores Entity Table and her Foreign Keys if exists
 */
if ( Mage::getSingleton('core/resource')->getConnection('core_write')->isTableExists($tableOfflineStores) ){
    $installer->getConnection()->dropForeignKey( $tableOfflineStores, 'FK_WEBINSE_OFFLINESTORES_STORE_ID_CORE_STORE_STORE_ID' );
    $installer->getConnection()->dropForeignKey( $tableOfflineStores, 'FK_WEBINSE_OFFLINESTORES_ENTT_TYPE_ID_EAV_ENTT_TYPE_ENTT_TYPE_ID' );
    $installer->getConnection()->dropTable($tableOfflineStores);
}

/**
 * Create all entity tables
 */
$installer->createEntityTables($tableOfflineStores);

/**
 * Create table 'webinseofflinestores/attribute_additional'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable($tableAdditionalAttribute))
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Attribute ID')
    ->addColumn('frontend_input_renderer', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Frontend Input Renderer')
    ->addColumn('is_global', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
    ), 'Is Global')
    ->addColumn('is_visible', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
    ), 'Is Visible')
    ->addColumn('is_searchable', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is Searchable')
    ->addColumn('is_filterable', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is Filterable')
    ->addColumn('is_comparable', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is Comparable')
    ->addColumn('is_visible_on_front', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is Visible On Front')
    ->addColumn('is_html_allowed_on_front', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is HTML Allowed On Front')
    ->addColumn('is_used_for_price_rules', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is Used For Price Rules')
    ->addColumn('is_filterable_in_search', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is Filterable In Search')
    ->addColumn('used_in_product_listing', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is Used In Product Listing')
    ->addColumn('used_for_sort_by', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is Used For Sorting')
    ->addColumn('is_configurable', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
    ), 'Is Configurable')
    ->addColumn('apply_to', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => true,
    ), 'Apply To')
    ->addColumn('is_visible_in_advanced_search', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is Visible In Advanced Search')
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'default'   => '0',
    ), 'Position')
    ->addColumn('is_wysiwyg_enabled', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is WYSIWYG Enabled')
    ->addColumn('is_used_for_promo_rules', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is Used For Promo Rules')
    ->addIndex($installer->getIdxName($tableAdditionalAttribute, array('used_for_sort_by')),
        array('used_for_sort_by'))
    ->addIndex($installer->getIdxName($tableAdditionalAttribute, array('used_in_product_listing')),
        array('used_in_product_listing'))
    ->addForeignKey($installer->getFkName($tableAdditionalAttribute, 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Catalog EAV Attribute Table');
$installer->getConnection()->createTable($table);

/**
 * Create table 'customer/eav_attribute_website'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable($tableEavAttributeWebsite))
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Attribute Id')
    ->addColumn('website_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Website Id')
    ->addColumn('is_visible', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
    ), 'Is Visible')
    ->addColumn('is_required', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
    ), 'Is Required')
    ->addColumn('default_value', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Default Value')
    ->addColumn('multiline_count', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
    ), 'Multiline Count')
    ->addIndex($installer->getIdxName($tableEavAttributeWebsite, array('website_id')),
        array('website_id'))
    ->addForeignKey(
        $installer->getFkName($tableEavAttributeWebsite, 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName($tableEavAttributeWebsite, 'website_id', 'core/website', 'website_id'),
        'website_id', $installer->getTable('core/website'), 'website_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Customer Eav Attribute Website');
$installer->getConnection()->createTable($table);

$installer->installEntities();
$installer->endSetup();