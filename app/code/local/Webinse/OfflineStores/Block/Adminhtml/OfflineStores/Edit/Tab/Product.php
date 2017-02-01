<?php
class Webinse_OfflineStores_Block_Adminhtml_OfflineStores_Edit_Tab_Product extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {

        $this->_blockGroup = 'webinseofflinestores';
        $this->_controller = 'adminhtml_offlinestores_edit_tab_product';
        /*$this->_blockGroup = 'edit_form';
        $this->_controller = 'adminhtml_sales_order';*/
        $this->_headerText = Mage::helper('webinseofflinestores')->__('OfflineStores - Products');

        parent::__construct();
        $this->_removeButton('add');
    }
}
