<?php
class Webinse_OfflineStores_Model_OfflineStore extends Mage_Core_Model_Abstract
{
    /**
     * Offline store model instance
     *
     * @deprecated if use as singleton
     * @var Mage_Catalog_Model_Product
     */
    protected $_offlineStore;

    /**
     * Is model readonly
     *
     * @var boolean
     */
    protected $_isReadonly = false;

    /**
     * Is model deleteable
     *
     * @var boolean
     */
    protected $_isDeleteable = true;

    /**
     * Assoc array of offline stores attributes
     *
     * @var array
     */
    protected $_attributes;

    /**
     * Initialize offlinestore model
     */
    public function _construct()
    {
        $this->_init('webinseofflinestores/offlinestore');
    }

    /**
     * Retrieve offline store object
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Mage_Catalog_Model_Product
     */
    public function getOfflineStore($offlineStore = null)
    {
        if (is_object($offlineStore)) {
            return $offlineStore;
        }
        return $this->_offlineStore;
    }

    /**
     * Checks model is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return $this->_isReadonly;
    }

    /**
     * Set is readonly flag
     *
     * @param boolean $value
     * @return Mage_Customer_Model_Customer
     */
    public function setIsReadonly($value)
    {
        $this->_isReadonly = (bool)$value;
        return $this;
    }

    /**
     * Checks model is deleteable
     *
     * @return boolean
     */
    public function isDeleteable()
    {
        return $this->_isDeleteable;
    }

    /**
     * Set is deleteable flag
     *
     * @param boolean $value
     * @return Mage_Customer_Model_Customer
     */
    public function setIsDeleteable($value)
    {
        $this->_isDeleteable = (bool)$value;
        return $this;
    }

    /**
     * Retrieve product attributes
     * if $groupId is null - retrieve all offline store attributes
     *
     * @param int  $groupId   Retrieve attributes of the specified group
     * @param bool $skipSuper Not used
     * @return array
     */
    public function getAttributes($groupId = null, $skipSuper = false)
    {
        $productAttributes = $this->getEditableAttributes($this);
        if ($groupId) {
            $attributes = array();
            foreach ($productAttributes as $attribute) {
                if ($attribute->isInGroup($this->getAttributeSetId(), $groupId)) {
                    $attributes[] = $attribute;
                }
            }
        } else {
            $attributes = $productAttributes;
        }
        return $attributes;
    }

    /**
     * Retrieve product type attributes
     *
     * @param Mage_Catalog_Model_Product $product
     * @return array
     */
    public function getEditableAttributes($offlineStore = null)
    {
        $cacheKey = '_cache_editable_attributes';
        if (!$this->getOfflineStore($offlineStore)->hasData($cacheKey)) {
            $editableAttributes = array();
            foreach ($this->getSetAttributes($offlineStore) as $attributeCode => $attribute) {
                if (!is_array($attribute->getApplyTo())
                    || count($attribute->getApplyTo())==0
                    || in_array($this->getOfflineStore($offlineStore)->getTypeId(), $attribute->getApplyTo())) {
                    $editableAttributes[$attributeCode] = $attribute;
                }
            }
            $this->getOfflineStore($offlineStore)->setData($cacheKey, $editableAttributes);
        }
        return $this->getOfflineStore($offlineStore)->getData($cacheKey);
    }
    /**
     * Get array of product set attributes
     *
     * @param Mage_Catalog_Model_Product $product
     * @return array
     */
    public function getSetAttributes($product = null)
    {
        return $this->getOfflineStore($product)->getResource()
            ->loadAllAttributes($this->getOfflineStore($product))
            ->getSortedAttributes($this->getOfflineStore($product)->getAttributeSetId());
    }
    public function getAttributeSetId()
    {
        return '14';
    }
}