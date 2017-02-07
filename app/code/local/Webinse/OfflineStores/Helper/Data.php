<?php
class Webinse_OfflineStores_Helper_Data extends Mage_Core_Helper_Abstract
{
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

    public function getMode()
    {
        return Mage::getStoreConfig('offlinestores/offlinestoresgroup/offlinestoreappearance');
    }

    public function getOfflineStoresPerPage()
    {
        return Mage::getStoreConfig('offlinestores/offlinestoresgroup/offlinestoresperpage');
    }

    public function getImagePath($id = 0, $ext = 'jpg')
    {
        $path = Mage::getBaseDir('media') . '/offlinestore';
        if ($id) {
            return "{$path}/{$id}.{$ext}";
        } else {
            return $path;
        }
    }

    public function getImageUrl($id = 0, $ext = 'jpg')
    {
        $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'offlinestore/';
        if ($id) {
            return $url . $id . '.'.$ext;
        } else {
            return $url;
        }
    }
}