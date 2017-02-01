<?php
class Webinse_OfflineStores_Model_Resource_Offlinestore_Collection extends Mage_Eav_Model_Entity_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('webinseofflinestores/offlinestore');
    }
}