<?php
class Webinse_OfflineStores_Model_Offlinestore_Url
{
    /**
     * Url instance
     *
     * @var Mage_Core_Model_Url
     */
    protected $_url;

    /**
     * Factory instance
     *
     * @var Mage_Catalog_Model_Factory
     */
    protected $_factory;

    /**
     * Url rewrite instance
     *
     * @var Mage_Core_Model_Url_Rewrite
     */
    protected $_urlRewrite;

    /**
     * Initialize Url model
     *
     * @param array $args
     */
    public function __construct(array $args = array())
    {
        $this->_factory = !empty($args['factory']) ? $args['factory'] : Mage::getSingleton('catalog/factory');
    }

    /**
     * Retrieve Url for specified offline store
     *
     * @param Webinse_OfflineStores_Model_Offlinestore $offlinestore
     * @return string
     */
    public function getOfflineStoreUrl(Webinse_OfflineStores_Model_Offlinestore $offlineStore)
    {
        $url = $offlineStore->getData('url');

        if (null !== $url) {
            return $url;
        }

        Varien_Profiler::start('REWRITE: '.__METHOD__);

        if ($offlineStore->hasData('request_path') && $offlineStore->getData('request_path') != '') {
            $offlineStore->setData('url', $this->_getDirectUrl($offlineStore));
            Varien_Profiler::stop('REWRITE: '.__METHOD__);
            return $offlineStore->getData('url');
        }

        $requestPath = $this->_getRequestPath($offlineStore);
        if ($requestPath) {
            $offlineStore->setRequestPath($requestPath);
            $offlineStore->setData('url', $this->_getDirectUrl($offlineStore));
            Varien_Profiler::stop('REWRITE: '.__METHOD__);
            return $offlineStore->getData('url');
        }

        Varien_Profiler::stop('REWRITE: '.__METHOD__);

        $offlineStore->setData('url', $offlineStore->getOfflineStoreIdUrl());
        return $offlineStore->getData('url');
    }

    /**
     * Retrieve request path
     *
     * @param Webinse_OfflineStores_Model_Offlinestore $offlineStore
     * @return bool|string
     */
    protected function _getRequestPath(Webinse_OfflineStores_Model_Offlinestore $offlineStore)
    {
        $rewrite = $this->getUrlRewrite();
        $storeId = $offlineStore->getStoreId();
        if ($storeId) {
            $rewrite->setStoreId($storeId);
        }
        $idPath = 'offlinestore/' . $offlineStore->getId();
        $rewrite->loadByIdPath($idPath);

        if ($rewrite->getId()) {
            return $rewrite->getRequestPath();
        }
        return false;
    }

    /**
     * Returns offline store URL by which it can be accessed
     * @param Webinse_OfflineStores_Model_Offlinestore $offlineStore
     * @return string
     */
    protected function _getDirectUrl(Webinse_OfflineStores_Model_Offlinestore $offlineStore)
    {
        return $this->getUrlInstance()->getDirectUrl($offlineStore->getRequestPath());
    }

    /**
     * Retrieve Url instance
     *
     * @return Mage_Core_Model_Url
     */
    public function getUrlInstance()
    {
        if (null === $this->_url) {
            $this->_url = $this->_factory->getModel('core/url');
        }
        return $this->_url;
    }

    /**
     * Retrieve Url rewrite instance
     *
     * @return Mage_Core_Model_Url_Rewrite
     */
    public function getUrlRewrite()
    {
        if (null === $this->_urlRewrite) {
            $this->_urlRewrite = $this->_factory->getUrlRewriteInstance();
        }
        return $this->_urlRewrite;
    }
}
