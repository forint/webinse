<?php
class Webinse_OfflineStores_Model_Resource_OfflineStores_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('webinseofflinestores/offlinestores');
    }
}