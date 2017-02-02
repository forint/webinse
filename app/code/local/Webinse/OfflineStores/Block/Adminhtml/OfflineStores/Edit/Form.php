<?php
class Webinse_Offlinestores_Block_Adminhtml_OfflineStores_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('offlinestores/widget/form.phtml');
        $this->setDestElementId('offlinestore_edit');
        $this->setShowGlobalIcon(false);
    }

    protected function _prepareForm(){
        /**
         * Form ID must specify to unique FORM-HTML ID
         * Dispatch and change form occur with Javascript, whitch take that ID
         */
        $form = new Varien_Data_Form(array(
            'id'     => 'offlinestore_edit',
            'action' => $this->getUrl('*/*/save', array('id'=>$this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype'=> 'multipart/form-data'
        ));

        $form->addField('in_offlinestore', 'hidden', array(
            'label' => Mage::helper('webinseofflinestores')->__('Offline Store Products'),
            'class' => '',
            'required' => false,
            'name' => 'in_offlinestore',
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

}