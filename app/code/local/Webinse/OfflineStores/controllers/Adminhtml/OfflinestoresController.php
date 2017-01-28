<?php
class Webinse_OfflineStores_Adminhtml_OfflinestoresController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('webinseofflinestores');
        $this->renderLayout();
    }

}