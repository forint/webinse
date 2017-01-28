<?php
/**
 * Created by PhpStorm.
 * User: forint
 * Date: 4/6/16
 * Time: 12:46 PM
 */

//die('DS News module setup');

$installer = $this;
$tableNews = $installer->getTable('dsnews/table_news');

//die($tableNews);


$installer->startSetup();
$installer->getConnection()->dropTable($tableNews);

$table = $installer->getConnection()->newTable($tableNews)
->addColumn('news_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'identity'  => true,
    'nullable'  => false,
    'primary'   => true,
))->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
    'nullable'  => false,
))->addColumn('content', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
    'nullable'  => false,
))->addColumn('created', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
    'nullable'  => false,
));
$installer->getConnection()->createTable($table);

$installer->endSetup();
