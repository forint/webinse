<?php
class Webinse_OfflineStores_Block_Adminhtml_Grid extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        die('!!!!!');
        //indicate where we can find the controller
        $this->_controller = 'adminhtml_offlinestores';
        $this->_blockGroup = 'offlinestores';

        // header text
        $this->_headerText = 'Manage my offline stores';

        // button label
        $this->_addButtonLabel = 'Add Offline Store';
        parent::__construct();
     }
}