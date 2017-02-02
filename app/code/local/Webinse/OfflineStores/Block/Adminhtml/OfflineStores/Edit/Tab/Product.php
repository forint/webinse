<?php
class Webinse_OfflineStores_Block_Adminhtml_OfflineStores_Edit_Tab_Product extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_blockGroup = 'webinseofflinestores';
        $this->_controller = 'adminhtml_offlinestores_edit_tab_product';

        $this->setTemplate('offlinestores/widget/grid/container.phtml');
        $this->_removeButton('add');
    }
}
