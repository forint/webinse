<?php
class Webinse_OfflineStores_Model_Product extends Mage_Catalog_Model_Abstract
{

    /**
     * Retrieve product categories
     *
     * @return Varien_Data_Collection
     */
    public function getCategoryCollection()
    {
        return $this->_getResource()->getCategoryCollection($this);
    }

}