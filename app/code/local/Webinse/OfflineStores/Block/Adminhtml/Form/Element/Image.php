<?php
class Webinse_OfflineStores_Block_Adminhtml_Form_Element_Image extends Varien_Data_Form_Element_Image
{
    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct()
    {
        $data = array(
            'label' => 'Image',
            'name'  => 'image'
        );
        parent::__construct($data);
        $this->setType('file');
        $this->_initImageValues();
    }

    /**
     * Load Image Data to Form Element
     */
    public function _initImageValues(){

        $offlineStoreId = Mage::registry('offlinestore');
        $value = Mage::helper('webinseofflinestores')->getImageUrl($offlineStoreId->getId());
        $this->setValue($value);

    }

}