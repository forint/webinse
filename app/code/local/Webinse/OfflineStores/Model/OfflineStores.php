<?php
class Webinse_OfflineStores_Model_OfflineStores extends Mage_Core_Model_Abstract {

    public function _construct(){
        parent::_construct();
        $this->_init('offlinestores/offlinestores');
    }



}