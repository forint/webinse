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
        $entityId = $this->getRequest()->getParam('id');

        $form = new Varien_Data_Form(array(
            'id'     => 'offlinestore_edit',
            'action' => $this->getUrl('*/*/save', array('id' => $entityId)),
            'method' => 'post',
            'enctype'=> 'multipart/form-data'
        ));
        // var_dump(get_class($form));die;
        // offlinestores_info_tabs_group_26_content
        // $fieldset = $form->addFieldset('offlinestores_info_tabs_group_26_content', array('legend' => $this->__('Template')));
        // $this->_addElementTypes($fieldset);

        $formData = array('image' => Mage::helper('webinseofflinestores')->getImageUrl($entityId));

        $form->setValues($formData);
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Return JSON object with countries associated to possible websites
     *
     * @return string
     */
    public function getDefaultCountriesJson() {
        $websites = Mage::getSingleton('adminhtml/system_store')->getWebsiteValuesForForm(false, true);
        $result = array();
        foreach ($websites as $website) {
            $result[$website['value']] = Mage::app()->getWebsite($website['value'])->getConfig(
                Mage_Core_Helper_Data::XML_PATH_DEFAULT_COUNTRY
            );
        }

        return Mage::helper('core')->jsonEncode($result);
    }

    public function getRegionsUrl()
    {
        return $this->getUrl('adminhtml/json/countryRegion');
    }

    public function getTemplatePrefix()
    {
        return '_template_';
    }

    /**
     * Retrieve predefined additional element types
     *
     * @return array
     */
    protected function _getAdditionalElementTypes()
    {
        return array();
    }

}