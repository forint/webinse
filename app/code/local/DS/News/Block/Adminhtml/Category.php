<?php
/**
 * Created by PhpStorm.
 * User: forint
 * Date: 4/8/16
 * Time: 3:40 PM
 */
class DS_News_Block_Adminhtml_Category extends Mage_Adminhtml_Block_Widget_Grid_Container {

    protected function _construct(){

        parent::_construct();

        $helper = Mage::helper('dsnews');
        $this->_blockGroup = 'dsnews';
        $this->_controller = 'adminhtml_category';

        $this->_headerText = $helper->__("Categories management");
        $this->_addButtonLabel = $helper->__('Add category');

    }

}