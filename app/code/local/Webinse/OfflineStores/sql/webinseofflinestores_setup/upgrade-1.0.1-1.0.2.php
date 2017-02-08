<?php
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;

$installer->startSetup();

$offlineStoreEntityTypeId = $installer->getEntityTypeId('offlinestore');
$installer->updateAttribute($offlineStoreEntityTypeId, 'description', 'is_wysiwyg_enabled', 1);
$installer->updateAttribute($offlineStoreEntityTypeId, 'description', 'is_html_allowed_on_front', 1);

$installer->updateAttribute($offlineStoreEntityTypeId, 'region_id', 'is_system', 1);

$installer->endSetup();