<?php
class Webinse_OfflineStores_Helper_Data extends Mage_Core_Helper_Abstract
{

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

}