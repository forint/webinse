<?php
class Webinse_OfflineStores_Model_Factory extends Mage_Core_Model_Factory
{
    /**
     * Xml path to the category url rewrite helper class alias
     */
    const XML_PATH_CATEGORY_URL_REWRITE_HELPER_CLASS = 'global/catalog/category/url_rewrite/helper';

    /**
     * Xml path to the product url rewrite helper class alias
     */
    const XML_PATH_PRODUCT_URL_REWRITE_HELPER_CLASS = 'global/catalog/product/url_rewrite/helper';

    /**
     * Path to offlinestore_url model alias
     */
    const XML_PATH_OFFLINE_STORE_URL_MODEL = 'global/offlinestore/product/url/model';

    /**
     * Path to category_url model alias
     */
    const XML_PATH_CATEGORY_URL_MODEL = 'global/catalog/category/url/model';

    /**
     * Retrieve offlinestore_url instance
     *
     * @return Webinse_OfflineStores_Model_Offlinestore_Url
     */
    public function getOfflineStoreUrlInstance()
    {
        return $this->getModel(
            (string)$this->_config->getNode(self::XML_PATH_OFFLINE_STORE_URL_MODEL)
        );
    }







    /**
     * Returns category url rewrite helper instance
     *
     * @return Mage_Catalog_Helper_Category_Url_Rewrite_Interface
     */
    public function getCategoryUrlRewriteHelper()
    {
        return $this->getHelper(
            (string)$this->_config->getNode(self::XML_PATH_CATEGORY_URL_REWRITE_HELPER_CLASS)
        );
    }

    /**
     * Returns product url rewrite helper instance
     *
     * @return Mage_Catalog_Helper_Product_Url_Rewrite_Interface
     */
    public function getProductUrlRewriteHelper()
    {
        return $this->getHelper(
            (string)$this->_config->getNode(self::XML_PATH_PRODUCT_URL_REWRITE_HELPER_CLASS)
        );
    }



    /**
     * Retrieve category_url instance
     *
     * @return Mage_Catalog_Model_Category_Url
     */
    public function getCategoryUrlInstance()
    {
        return $this->getModel(
            (string)$this->_config->getNode(self::XML_PATH_CATEGORY_URL_MODEL)
        );
    }
}
