<?php
class Webinse_OfflineStores_Model_Factory extends Mage_Core_Model_Factory
{
    /**
     * Path to offlinestore_url model alias
     */
    const XML_PATH_OFFLINE_STORE_URL_MODEL = 'global/offlinestore/product/url/model';

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

}
