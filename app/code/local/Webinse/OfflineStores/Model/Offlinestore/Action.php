<?php
class Webinse_OfflineStores_Model_Offlinestore_Action extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('webinseofflinestores/offlinestore_action');
    }

    /**
     * Retrieve resource instance wrapper
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Action
     */
    protected function _getResource()
    {
        return parent::_getResource();
    }

    /**
     * Update attribute values for entity list per store
     *
     * @param array $productIds
     * @param array $attrData
     * @param int $storeId
     * @return Mage_Catalog_Model_Product_Action
     */
    public function updateAttributes($offlineStoreIds, $attrData, $storeId)
    {
        Mage::dispatchEvent('offline_store_attribute_update_before', array(
            'attributes_data' => &$attrData,
            'offlinestore_ids'   => &$offlineStoreIds,
            'store_id'      => &$storeId
        ));

        $this->_getResource()->updateAttributes($offlineStoreIds, $attrData, $storeId);
        $this->setData(array(
            'offlinestore_ids'       => array_unique($offlineStoreIds),
            'attributes_data'   => $attrData,
            'store_id'          => $storeId
        ));

        // register mass action indexer event
        /*Mage::getSingleton('index/indexer')->processEntityAction(
            $this, Mage_Catalog_Model_Product::ENTITY, Mage_Index_Model_Event::TYPE_MASS_ACTION
        );*/

        Mage::dispatchEvent('offline_store_attribute_update_after', array(
            'offlinestore_ids'   => $offlineStoreIds,
        ));

        return $this;
    }

    /**
     * Update websites for product action
     *
     * allowed types:
     * - add
     * - remove
     *
     * @param array $productIds
     * @param array $websiteIds
     * @param string $type
     */
    public function updateWebsites($productIds, $websiteIds, $type)
    {
        Mage::dispatchEvent('catalog_product_website_update_before', array(
            'website_ids'   => $websiteIds,
            'product_ids'   => $productIds,
            'action'        => $type
        ));

        if ($type == 'add') {
            Mage::getModel('catalog/product_website')->addProducts($websiteIds, $productIds);
        } else if ($type == 'remove') {
            Mage::getModel('catalog/product_website')->removeProducts($websiteIds, $productIds);
        }

        $this->setData(array(
            'product_ids' => array_unique($productIds),
            'website_ids' => $websiteIds,
            'action_type' => $type
        ));

        // register mass action indexer event
        Mage::getSingleton('index/indexer')->processEntityAction(
            $this, Mage_Catalog_Model_Product::ENTITY, Mage_Index_Model_Event::TYPE_MASS_ACTION
        );

        // add back compatibility system event
        Mage::dispatchEvent('catalog_product_website_update', array(
            'website_ids'   => $websiteIds,
            'product_ids'   => $productIds,
            'action'        => $type
        ));
    }
}
