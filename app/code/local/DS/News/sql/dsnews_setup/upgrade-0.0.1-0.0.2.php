<?php
/**
 * Created by PhpStorm.
 * User: forint
 * Date: 4/8/16
 * Time: 2:13 PM
 */
//echo '<h1>Upgrade DS News to version 0.0.2</h1>';exit;

$installer = $this;
$tableCategories = $installer->getTable('dsnews/table_categories');
$tableNews = $installer->getTable('dsnews/table_news');

$installer->startSetup();
$installer->getConnection()->dropTable($tableCategories);
$table = $installer->getConnection()
    ->newTable($tableCategories)
    ->addColumn('category_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
    ))->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
        'nullable'  => false,
    ));

$installer->getConnection()->createTable($table);

$installer->getConnection()->addColumn($tableNews, 'category_id', array(
    'comment'   => 'News Category',
    'default'   => '0',
    'nullable'  => false,
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
));

$installer->endSetup();
