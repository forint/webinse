<?php
class Webinse_OfflineStores_Model_Layer extends Varien_Object
{
    /**
     * Product collections array
     *
     * @var array
     */
    protected $_productCollections = array();

    /**
     * Retrieve current layer offline store collection
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getOfflineStoreCollection()
    {
        $collection = Mage::getResourceModel('webinseofflinestores/offlinestore_collection')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('image')
            ->addAttributeToSelect('short_description')
            ->addAttributeToSelect('description')
            ->addAttributeToSelect('disposition')
            ->load();

        return $collection;
    }

    /**
     * Retrieve current layer product collection
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getProductCollection()
    {
        if (isset($this->_productCollections[$this->getCurrentOfflineStore()->getId()])) {
            $collection = $this->_productCollections[$this->getCurrentOfflineStore()->getId()];
        } else {
            $collection = $this->getCurrentOfflineStore()->getProductCollection();
            $this->prepareProductCollection($collection);
            $this->_productCollections[$this->getCurrentOfflineStore()->getId()] = $collection;
        }
        return $collection;
    }

    /**
     * Retrieve current offline store model
     * If no offline store found in registry
     *
     * @return Webinse_OfflineStores_Model_Offlinestore
     */
    public function getCurrentOfflineStore()
    {
        $offlineStore = $this->getData('offlinestore');
        if (is_null($offlineStore)) {
            if ($offlineStore = Mage::registry('offlinestore')) {
                $this->setData('offlinestore', $offlineStore);
            }
        }

        return $offlineStore;
    }

    /**
     * Initialize product collection
     *
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection
     * @return Webinse_OfflineStores_Model_Layer
     */
    public function prepareProductCollection($collection)
    {
        $collection
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            /*->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()*/
            //->addUrlRewrite($this->getCurrentCategory()->getId())
        ;

        // Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        // Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

        return $this;
    }
}
