<?php
class Webinse_OfflineStores_Block_Adminhtml_Offlinestores_Edit_Tab_Attributes extends Mage_Adminhtml_Block_Catalog_Form
{
    /**
     * Load Wysiwyg on demand and prepare layout
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::helper('catalog')->isModuleEnabled('Mage_Cms')
            && Mage::getSingleton('cms/wysiwyg_config')->isEnabled()
        ) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }


    /**
     * Retrieve additional element types
     *
     * @return array
     */
    protected function _getAdditionalElementTypes()
    {
        $result = array(
            'image'    => Mage::getConfig()->getBlockClassName('webinseofflinestores/adminhtml_form_element_image'),
            'boolean'  => Mage::getConfig()->getBlockClassName('adminhtml/catalog_product_helper_form_boolean'),
            'textarea' => Mage::getConfig()->getBlockClassName('adminhtml/catalog_helper_form_wysiwyg')
        );

        $response = new Varien_Object();
        $response->setTypes(array());
        Mage::dispatchEvent('adminhtml_catalog_product_edit_element_types', array('response' => $response));

        foreach ($response->getTypes() as $typeName => $typeClass) {
            $result[$typeName] = $typeClass;
        }

        return $result;
    }

    /**
     * Prepare attributes form
     *
     * @return null
     */
    protected function _prepareForm()
    {
        $group = $this->getGroup();
        if ($group) {
            $form = new Varien_Data_Form();
            $form->setHtmlIdPrefix('_template_');
            // Initialize product object as form property to use it during elements generation
            $form->setDataObject(Mage::registry('offlinestore'));

            $fieldset = $form->addFieldset('group_fields' . $group->getId(), array(
                'legend' => Mage::helper('catalog')->__($group->getAttributeGroupName()),
                'class' => 'fieldset-wide'
            ));

            $attributes = $this->getGroupAttributes();
            $this->_setFieldset($attributes, $fieldset, array('gallery'));
            $this->_addElementTypes($fieldset);

            $regionElement = $form->getElement('region');
            if ($regionElement) {
                $isRequired = Mage::helper('directory')->isRegionRequired(Mage::registry('offlinestore')->getCountryId());
                $regionElement->setRequired($isRequired);
                $regionElement->setRenderer(Mage::getModel('webinseofflinestores/renderer_region'));
            }

            $regionElementId = $form->getElement('region_id');
            if ($regionElementId) {
                //var_dump(get_class($regionElementId));die;
                $regionElementId->setNoDisplay(true);
                $regionElementId->setRenderer(Mage::getBlockSingleton('webinseofflinestores/adminhtml_renderer_region'));

            }

            $country = $form->getElement('country_id');
            if ($country) {
                $country->addClass('countries');
            }

            $values = Mage::registry('offlinestore')->getData();

            if (Mage::registry('offlinestore')->hasLockedAttributes()) {
                foreach (Mage::registry('offlinestore')->getLockedAttributes() as $attribute) {
                    $element = $form->getElement($attribute);
                    if ($element) {
                        $element->setReadonly(true, true);
                    }
                }
            }
            $form->addValues($values);
            $form->setFieldNameSuffix('offlinestore');

            Mage::dispatchEvent('adminhtml_offlinestore_edit_prepare_form', array('form' => $form));

            $this->setForm($form);
        }
    }

    protected function _afterToHtml($html)
    {
        return $html;
    }
}
