<?php
/**
 * Created by PhpStorm.
 * User: forint
 * Date: 4/8/16
 * Time: 1:22 PM
 */

class DS_News_Block_Adminhtml_News_Edit_Tabs_General extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm(){

        $helper = Mage::helper('dsnews');
        $model = Mage::registry('current_news');


        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('general_form', array(
            'legend' => $helper->__('General Information')
        ));

        $fieldset->addField('title', 'text', array(
            'label' => $helper->__('Title'),
            'required' => true,
            'name' => 'title',
        ));

        $fieldset->addField('content', 'editor', array(
            'label' => $helper->__('Content'),
            'required' => true,
            'name' => 'content',
        ));

        $fieldset->addField('image', 'image', array(
            'label' => $helper->__('Image'),
            'name'  => 'image'
        ));

        $fieldset->addField('created', 'date', array(
            'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'label' => $helper->__('Created'),
            'name' => 'created'
        ));

        $fieldset->addField('category_id', 'select', array(
            'label' => $helper->__('Category'),
            'name' => 'category_id',
            'values' => $helper->getCategoriesOptions()
        ));

        $formData = array_merge($model->getData(), array('image'=> $model->getImageUrl()));
        $form->setValues($formData);
        $this->setForm($form);

        return parent::_prepareForm();

    }

}