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
     * Prepare attributes form
     *
     * @return null
     */
    protected function _prepareForm()
    {
        $group = $this->getGroup();
        if ($group) {
            $form = new Varien_Data_Form();

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
                $regionElement->setRenderer(Mage::getModel('adminhtml/customer_renderer_region'));
            }

            $regionElement = $form->getElement('region_id');
            if ($regionElement) {
                $regionElement->setNoDisplay(true);
            }

            $country = $form->getElement('country_id');
            if ($country) {
                $country->addClass('countries');
            }

           /* foreach($fieldset->getElements() as $element){
                print_r('<pre>');
                print_r(get_class($element));
                print_r('</pre>');
                echo PHP_EOL;

            }
            die;*/
            // Add image renderer
            $image = $form->getElement('image');
            if ($image){
                // $image->setIdFieldName('Image');
                // var_dump(get_class_methods($form));die;
                $form->setRenderer(Mage::getBlockSingleton('webinseofflinestores/adminhtml_form_element_image'));
            }


            $values = Mage::registry('offlinestore')->getData();

            // Set default attribute values for new product
            if (!Mage::registry('offlinestore')->getId()) {
                foreach ($attributes as $attribute) {
                    if (!isset($values[$attribute->getAttributeCode()])) {
                        $values[$attribute->getAttributeCode()] = $attribute->getDefaultValue();
                    }
                }
            }

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

    /**
     * Retrieve additional element types
     *
     * @return array
     */
    protected function _getAdditionalElementTypes()
    {
        $result = array(
            //'image'    => Mage::getConfig()->getBlockClassName('adminhtml/catalog_product_helper_form_image'),
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

}
