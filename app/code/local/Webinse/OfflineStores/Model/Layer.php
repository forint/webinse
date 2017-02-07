<?php
class Webinse_OfflineStores_Model_Layer extends Varien_Object
{
    /**
     * Retrieve current layer offline store collection
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
    public function getOfflineStoreCollection()
    {
        $collection = Mage::getResourceModel('webinseofflinestores/offlinestore_collection')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('image')
            ->addAttributeToSelect('short_description')
            ->addAttributeToSelect('description')
            ->addAttributeToSelect('disposition')
            ->load();

        return $collection;
    }


}
