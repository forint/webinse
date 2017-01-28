<?php
/**
 * Created by PhpStorm.
 * User: forint
 * Date: 4/8/16
 * Time: 3:58 PM
 */
class DS_News_Block_Adminhtml_Category_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    protected function _construct()
    {
        $this->_blockGroup = 'dsnews';
        $this->_controller = 'adminhtml_category';
    }

    public function getHeaderText()
    {
        $helper = Mage::helper('dsnews');
        $model = Mage::registry('current_category');

        if ($model->getId()) {
            return $helper->__("Edit Category '%s'", $this->escapeHtml($model->getTitle()));
        } else {
            return $helper->__("Add Category");
        }
    }

}