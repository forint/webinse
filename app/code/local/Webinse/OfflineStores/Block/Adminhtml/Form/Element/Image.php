<?php
class Webinse_OfflineStores_Block_Adminhtml_Form_Element_Image extends Varien_Data_Form_Element_Image
{

    public $_value = false;
    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct()
    {
        $this->setType('file');
        $this->setLabel('Image');
        $this->setName('image');
        // $this->_initImageValues();
    }

    /**
     * Load Image Data to Form Element
     */
    public function _initImageValues(){

        $offlineStoreId = Mage::registry('offlinestore');
        $value = Mage::helper('webinseofflinestores')->getImageUrl($offlineStoreId->getId());
        $this->setValue($value);

    }

    /**
     * Retrieve offline store image url
     * @return bool|string
     */
    protected function _getUrl()
    {
        $offlineStoreId = Mage::registry('offlinestore');
        return Mage::helper('webinseofflinestores')->getImageUrl($offlineStoreId->getId());
    }


}