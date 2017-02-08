<?php
class Webinse_OfflineStores_Model_Resource_Offlinestore_Collection extends Mage_Eav_Model_Entity_Collection_Abstract
{

    /**
     * Is add URL rewrites to collection flag
     *
     * @var bool
     */
    protected $_addUrlRewrite                = false;

    /**
     * Add URL rewrite for offline store
     *
     * @var int
     */
    protected $_urlRewriteOfflineStore           = '';

    /**
     * Offline store factory instance
     *
     * @var Webinse_OfflineStores_Model_Factory
     */
    protected $_factory;

    /**
     * Initialize offline store collection
     */
    public function _construct()
    {
        $this->_init('webinseofflinestores/offlinestore');
        $this->_factory = Mage::getSingleton('webinseofflinestores/factory');
    }

    /**
     * Add tax class id attribute to select and join price rules data if needed
     *
     * @return Webinse_OfflineStores_Model_Resource_Offlinestore_Collection
     */
    protected function _beforeLoad()
    {
        Mage::dispatchEvent('offline_store_collection_load_before', array('collection' => $this));

        return parent::_beforeLoad();
    }

    /**
     * Processing collection items after loading
     * Adding url rewrites
     *
     * @return Webinse_OfflineStores_Model_Resource_Offlinestore_Collection
     */
    protected function _afterLoad()
    {
        if ($this->_addUrlRewrite) {
            $this->_addUrlRewrite($this->_urlRewriteCategory);
        }

        if (count($this) > 0) {
            Mage::dispatchEvent('offline_store_collection_load_after', array('collection' => $this));
        }

        return $this;
    }

    /**
     * Add URL rewrites data to offline store
     * If collection loadded - run processing else set flag
     *
     * @param int|string $offlineStoreId
     * @return Webinse_OfflineStores_Model_Resource_Offlinestore_Collection
     */
    public function addUrlRewrite($offlineStoreId = '')
    {
        die('addUrlRewrite');
        $this->_addUrlRewrite = true;
        if (Mage::getStoreConfig(Mage_Catalog_Helper_Product::XML_PATH_PRODUCT_URL_USE_CATEGORY, $this->getStoreId())) {
            $this->_urlRewriteOfflineStore = $offlineStoreId;
        } else {
            $this->_urlRewriteOfflineStore = 0;
        }

        if ($this->isLoaded()) {
            $this->_addUrlRewrite();
        }

        return $this;
    }

    /**
     * Add URL rewrites to collection
     */
    protected function _addUrlRewrite()
    {
        $urlRewrites = null;
        if ($this->_cacheConf) {
            if (!($urlRewrites = Mage::app()->loadCache($this->_cacheConf['prefix'] . 'urlrewrite'))) {
                $urlRewrites = null;
            } else {
                $urlRewrites = unserialize($urlRewrites);
            }
        }

        if (!$urlRewrites) {
            $offlineStoreIds = array();
            foreach($this->getItems() as $item) {
                $offlineStoreIds[] = $item->getEntityId();
            }
            if (!count($offlineStoreIds)) {
                return;
            }

            var_dump(get_class($this->_factory));die;
            $select = $this->_factory->getProductUrlRewriteHelper()
                ->getTableSelect($offlineStoreIds, $this->_urlRewriteCategory, Mage::app()->getStore()->getId());

            $urlRewrites = array();
            foreach ($this->getConnection()->fetchAll($select) as $row) {
                if (!isset($urlRewrites[$row['product_id']])) {
                    $urlRewrites[$row['product_id']] = $row['request_path'];
                }
            }

            if ($this->_cacheConf) {
                Mage::app()->saveCache(
                    serialize($urlRewrites),
                    $this->_cacheConf['prefix'] . 'urlrewrite',
                    array_merge($this->_cacheConf['tags'], array(Mage_Catalog_Model_Product_Url::CACHE_TAG)),
                    $this->_cacheLifetime
                );
            }
        }

        foreach($this->getItems() as $item) {
            if (empty($this->_urlRewriteCategory)) {
                $item->setDoNotUseCategoryId(true);
            }
            if (isset($urlRewrites[$item->getEntityId()])) {
                $item->setData('request_path', $urlRewrites[$item->getEntityId()]);
            } else {
                $item->setData('request_path', false);
            }
        }
    }

    /**
     * Specify product filter for offline stores collection
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Webinse_OfflineStores_Model_Resource_Product_Collection
     */
    public function addProductFilter(Mage_Catalog_Model_Product $product)
    {

        /** @var Relation Between Table $conditions */
        $conditions = array(
            'offlinestores_product.offlinestore_id=e.entity_id',
            $this->getConnection()->quoteInto('offlinestores_product.product_id=?', $product->getId())
        );

        $joinCond = join(' AND ', $conditions);
        $fromPart = $this->getSelect()->getPart(Zend_Db_Select::FROM);
        if (isset($fromPart['offlinestores_product'])) {
            $fromPart['offlinestores_product']['joinCondition'] = $joinCond;
            $this->getSelect()->setPart(Zend_Db_Select::FROM, $fromPart);
        }
        else {
            $this->getSelect()->join(
                array('offlinestores_product' => $this->getTable('webinseofflinestores/offlinestores_product')),
                $joinCond,
                array('offlinestores_product_index_position' => 'position')
            );
        }

        /** Join Additional Enitity Table for retrieve Entity Values */
        /*$this->getSelect()->joinLeft(
            array("offlinestores_values"=>'webinse_offlinestores_varchar'),
            'e.entity_id = offlinestores_values.entity_id',
            array('offlinestores_values' => "value")
        );*/

        return $this;
    }
}