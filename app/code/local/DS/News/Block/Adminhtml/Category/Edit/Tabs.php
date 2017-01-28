<?php

class DS_News_Block_Adminhtml_Category_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        $helper = Mage::helper('dsnews');

        parent::__construct();
        $this->setId('category_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($helper->__('Category Information'));
    }

    protected function _prepareLayout()
    {
        $helper = Mage::helper('dsnews');
        $category = Mage::registry('current_category');

        $this->addTab('general_section', array(
            'label' => $helper->__('General Information'),
            'title' => $helper->__('General Information'),
            'content' => $this->getLayout()->createBlock('dsnews/adminhtml_category_edit_tabs_general')->toHtml(),
        ));

        if ($category->getId()){
            $this->addTab('news_section', array(
                'class' => 'ajax',
                'label' => $helper->__('News'),
                'title' => $helper->__('News'),
                'url' => $this->getUrl('*/*/news', array('_current' => true)),
            ));
        }
        return parent::_prepareLayout();
    }

}
