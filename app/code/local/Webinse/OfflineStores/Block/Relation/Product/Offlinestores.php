<?php
class Webinse_OfflineStores_Block_Relation_Product_Offlinestores extends Mage_Core_Block_Template
{
    /**
     * Retrieve Offline Stores related with current product
     *
     * @return mixed
     */
    public function getOfflineStores()
    {
        $_product = Mage::registry('current_product');

        $_collection = Mage::getModel('webinseofflinestores/offlinestore')
            ->getOfflineStoresCollection($_product);

        return $_collection;
    }
}