<?php
class Webinse_OfflineStores_ViewController extends Mage_Core_Controller_Front_Action {

    /**
     * Initialize offline store object
     * Create offline store registry
     *
     * @param string $idFieldName
     * @return false|Mage_Core_Model_Abstract
     */
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

    /**
     * Index action for offline store entity
     */
    public function indexAction()
    {
        $this->_initOfflinestore();
        $this->loadLayout();
        $this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
        $this->renderLayout();
    }

}