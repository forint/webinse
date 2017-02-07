<?php
class Webinse_OfflineStores_Block_Offlinestore_View extends Mage_Core_Block_Template
{
    /**
     * Retrieve current offline store model object
     *
     * @return Webinse_OfflineStores_Model_Offlinestore
     */
    public function getCurrentOfflineStore()
    {
        if (!$this->hasData('offlinestore')) {
            $this->setData('offlinestore', Mage::registry('offlinestore'));
        }
        return $this->getData('offlinestore');
    }

    public function getProductListHtml()
    {
        return $this->getChildHtml('product_list');
    }
    /*protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->getLayout()->createBlock('catalog/breadcrumbs');

        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $category = $this->getCurrentCategory();
            if ($title = $category->getMetaTitle()) {
                $headBlock->setTitle($title);
            }
            if ($description = $category->getMetaDescription()) {
                $headBlock->setDescription($description);
            }
            if ($keywords = $category->getMetaKeywords()) {
                $headBlock->setKeywords($keywords);
            }
            if ($this->helper('catalog/category')->canUseCanonicalTag()) {
                $headBlock->addLinkRel('canonical', $category->getUrl());
            }

            if ($this->IsRssCatalogEnable() && $this->IsTopCategory()) {
                $title = $this->helper('rss')->__('%s RSS Feed',$this->getCurrentCategory()->getName());
                $headBlock->addItem('rss', $this->getRssLink(), 'title="'.$title.'"');
            }
        }

        return $this;
    }*/


}