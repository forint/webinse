<?php
class Webinse_OfflineStores_Block_Adminhtml_OfflineStores_Tab_Product extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('offlinestore_products');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    public function getOfflineStore()
    {
        return Mage::registry('offlinestore');
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    protected function _getSelectedProducts()
    {
        $products = $this->getRequest()->getPost('selected_products');
        if (is_null($products)) {
            $products = $this->getOfflineStore()->getProductsPosition();
            return array_keys($products);
        }
        return $products;
    }

}

