<?php
class Webinse_OfflineStores_Block_Widget_Offlinestore extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{
    /**
     * Offline Store Collection
     *
     * @var Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected $_offlineStoreCollection;

    /**
     * Retrieve loaded offline store collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getLoadedOfflineStoreCollection()
    {
        return $this->_getOfflineStoreCollection();
    }

    /**
     * Retrieve loaded offline store collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getOfflineStoreCollection()
    {
        if (is_null($this->_offlineStoreCollection)) {

            /* @var $layer Webinse_OfflineStores_Model_Layer */
            $layer = $this->getLayer();
            $this->_offlineStoreCollection = $layer->getOfflineStoreCollection();
        }

        return $this->_offlineStoreCollection;
    }

    /**
     * Get offline store catalog layer model
     *
     * @return Mage_Catalog_Model_Layer
     */
    public function getLayer()
    {
        $layer = Mage::registry('current_layer');
        if ($layer) {
            return $layer;
        }
        return Mage::getSingleton('webinseofflinestores/layer');
    }

    /**
     * Retrieve list toolbar HTML
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    /**
     * Retrieve appearence mode from system configuration
     * @return mixed
     */
    public function getMode(){
        return Mage::helper('webinseofflinestores')->getMode();
    }
}