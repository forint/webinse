<?php
/**
 * Created by PhpStorm.
 * User: forint
 * Date: 4/6/16
 * Time: 4:47 PM
 */
class DS_News_Block_Adminhtml_News extends Mage_Adminhtml_Block_Widget_Grid_Container {

    protected function _construct(){
        
        parent::_construct();
        
        $helper = Mage::helper('dsnews');
        $this->_blockGroup = 'dsnews';
        $this->_controller = 'adminhtml_news';

        $this->_headerText = $helper->__("News management");
        $this->_addButtonLabel = $helper->__('Add news');
    }
    
}