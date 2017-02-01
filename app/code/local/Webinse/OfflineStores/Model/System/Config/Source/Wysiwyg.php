<?php
class Webinse_OfflineStores_Model_System_Config_Source_Wysiwyg extends Mage_Adminhtml_Block_Catalog_Helper_Form_Wysiwyg
{
    protected function _getAdditionalElementTypes()
    {
        return array(
            'image' => Mage::getConfig()->getBlockClassName('adminhtml/catalog_category_helper_image'),
            'textarea' => Mage::getConfig()->getBlockClassName('adminhtml/catalog_helper_form_wysiwyg')
        );
    }
}