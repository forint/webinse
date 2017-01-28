<?php
class Webinse_OfflineStores_Block_Adminhtml_OfflineStores extends Mage_Adminhtml_Block_Widget_Container
{
    /**
     * Set template
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('offlinestores/list.phtml');
    }

    /**
     * Prepare button and grid
     *
     * @return Webinse_OfflineStores_Block_Adminhtml_OfflineStores
     */
    protected function _prepareLayout()
    {
        $this->_addButton('add_new', array(
            'label'   => Mage::helper('catalog')->__('Add Offline Store'),
            'onclick' => "setLocation('{$this->getUrl('*/*/new')}')",
            'class'   => 'add'
        ));

        $this->setChild('grid', $this->getLayout()->createBlock('webinseofflinestores/adminhtml_offlinestores_grid', 'adminhtml.offlinestores.grid'));
        return parent::_prepareLayout();
    }

    /**
     * Deprecated since 1.3.2
     *
     * @return string
     */
    public function getAddNewButtonHtml()
    {
        return $this->getChildHtml('add_new_button');
    }

    /**
     * Render grid
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    /**
     * Check whether it is single store mode
     *
     * @return bool
     */
    public function isSingleStoreMode()
    {
        if (!Mage::app()->isSingleStoreMode()) {
            return false;
        }
        return true;
    }
}
