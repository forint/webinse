<?php
class Webinse_Offlinestores_Block_Adminhtml_OfflineStores_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('offlinestores/widget/form.phtml');
        $this->setDestElementId('offlinestores_edit_form');
        $this->setShowGlobalIcon(false);
    }

    protected function _prepareForm(){
        /*
         * id должен указываться уникальный HTML id формы на странице,
         * т.к. отправка и изменение формы происходит JavaScript-ом,
         * который обращается по данному айди
         */
        $form = new Varien_Data_Form(array(
            'id'     => 'offlinestores_edit_form',
            'action' => $this->getUrl('*/*/save', array('id'=>$this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype'=> 'multipart/form-data'
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}