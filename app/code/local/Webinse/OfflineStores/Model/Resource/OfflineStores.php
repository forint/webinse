<?php
class Webinse_OfflineStores_Model_Resource_OfflineStores extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('webinse_offlinestores/table_offlinestores', 'offlinestore_id');
    }

}
