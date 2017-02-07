<?php
class Webinse_OfflineStores_ViewController extends Mage_Core_Controller_Front_Action {

    public function _initOfflinestore($idFieldName = 'id')
    {
        $offlineStoreId = (int)$this->getRequest()->getParam($idFieldName, false);
        $offlineStore = Mage::getModel('webinseofflinestores/offlinestore');

        if ($offlineStoreId) {
            $offlineStore->load($offlineStoreId);
        }

        Mage::register('offlinestore', $offlineStore);

        return $offlineStore;
    }

    public function indexAction()
    {
        $this->_initOfflinestore();
        $this->loadLayout();
        $this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
        $this->renderLayout();
    }

}