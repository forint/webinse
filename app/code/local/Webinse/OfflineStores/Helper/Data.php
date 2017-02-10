<?php
class Webinse_OfflineStores_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_CONTENT_TEMPLATE_FILTER = 'global/offlinestore/content/tempate_filter';
    const CONFIG_PARSE_URL_DIRECTIVES      = 'offlinestore/frontend/parse_url_directives';

    /**
     * Default value for count offline stores in setting
     */
    private $_perpage = '20';

    /**
     * Default value for display offline store in setting (0 - list, 1 - grid)
     */
    private $_appearance = '0';

    /**
     * Attribute Tab block name for Offlinestore edit
     *
     * @var string
     */
    protected $_attributeTabBlock = null;

    /**
     * Currently selected store ID if applicable
     *
     * @var int
     */
    protected $_storeId = null;

    public function __construct()
    {
        $this->setStoreId(Mage::app()->getStore()->getId());
    }

    /**
     * Set a specified store ID value
     *
     * @param int $store
     * @return Webinse_OfflineStores_Helper_Data
     */
    public function setStoreId($store)
    {
        $this->_storeId = $store;
        return $this;
    }

    /**
     * Retrieve Attribute Tab Block Name for Offlinestore Edit
     *
     * @return string
     */
    public function getAttributeTabBlock()
    {
        return $this->_attributeTabBlock;
    }

    /**
     * Set Custom Attribute Tab Block Name for Offlinestore Edit
     *
     * @param string $attributeTabBlock
     * @return Webinse_OfflineStores_Helper_Data
     */
    public function setAttributeTabBlock($attributeTabBlock)
    {
        $this->_attributeTabBlock = $attributeTabBlock;
        return $this;
    }

    /**
     * Retrive count offlinestores entities display mode from system preferences
     * If system value not set, get default value
     *
     * @return mixed|string
     */
    public function getMode()
    {
        $mode = Mage::getStoreConfig('offlinestores/offlinestoresgroup/offlinestoreappearance');
        if (isset($mode) && !empty($mode))
            return $mode;

        return $this->_appearance;
    }

    /**
     * Retrive count offlinestores entities per page from system preferences
     * If system value not set, get default value
     *
     * @return mixed|string
     */
    public function getOfflineStoresPerPage()
    {
        $perpage = Mage::getStoreConfig('offlinestores/offlinestoresgroup/offlinestoresperpage');
        if (isset($perpage) && is_numeric($perpage))
            return $perpage;

        return $this->_perpage;
    }

    /**
     * Offline store image path
     *
     * @param int $id
     * @param string $ext
     * @return string
     */
    public function getImagePath($id = 0, $ext = 'jpg')
    {
        $path = Mage::getBaseDir('media') . '/offlinestore';
        if ($id) {
            return "{$path}/{$id}.{$ext}";
        } else {
            return $path;
        }
    }

    /**
     * Offline store image url
     *
     * @param int $id
     * @param string $ext
     * @return string
     */
    public function getImageUrl($id = 0, $ext = 'jpg')
    {
        $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'offlinestore/';
        if ($id) {
            return $url . $id . '.'.$ext;
        } else {
            return $url;
        }
    }

    /**
     * Check if the parsing of URL directives is allowed for the offline stores catalog
     *
     * @return bool
     */
    public function isUrlDirectivesParsingAllowed()
    {
        return Mage::getStoreConfigFlag(self::CONFIG_PARSE_URL_DIRECTIVES, $this->_storeId);
    }

    /**
     * Retrieve template processor for offline stores catalog content
     *
     * @return Varien_Filter_Template
     */
    public function getPageTemplateProcessor()
    {
        $model = (string)Mage::getConfig()->getNode(self::XML_PATH_CONTENT_TEMPLATE_FILTER);
        return Mage::getModel($model);
    }
}