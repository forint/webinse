<?php
class Webinse_OfflineStores_Model_Offlinestore extends Mage_Core_Model_Abstract
{
    const DM_PRODUCT            = 'PRODUCTS';

    /**
     * Offline store model instance
     *
     * @var Webinse_OfflineStores_Model_OfflineStore
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
     * Offline Store Url Instance
     *
     * @var Mage_Catalog_Model_Offlinestore_Url
     */
    protected $_urlModel = null;

    /**
     * URL Model instance
     *
     * @var Mage_Core_Model_Url
     */
    protected static $_url;

    protected $_eventPrefix = 'webinse_offlinestores';

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

    /**
     * Validate Product Data
     *
     * @todo implement full validation process with errors returning which are ignoring now
     *
     * @return Mage_Catalog_Model_Product
     */
    public function validate()
    {
        Mage::dispatchEvent($this->_eventPrefix.'_validate_before', array($this->_eventObject=>$this));
        $this->_getResource()->validate($this);
        Mage::dispatchEvent($this->_eventPrefix.'_validate_after', array($this->_eventObject=>$this));
        return $this;
    }

    /**
     * Retrieve resource instance wrapper
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product
     */
    protected function _getResource()
    {
        return parent::_getResource();
    }

    /**
     * Add data to the object.
     *
     * Retains previous data in the object.
     *
     * @param array $arr
     * @return Varien_Object
     */
    public function addData(array $arr)
    {
        foreach($arr as $index=>$value) {
            $this->setData($index, $value);
        }
        return $this;
    }

    /**
     * Retrieve array of product id's for offline store
     *
     * array($productId => $position)
     *
     * @return array
     */
    public function getProductsPosition()
    {
        if (!$this->getId()) {
            return array();
        }
        $array = $this->getData('products_position');
        if (is_null($array)) {
            $array = $this->getResource()->getProductsPosition($this);
            $this->setData('products_position', $array);
        }
        return $array;
    }

    /**
     * Return Entity Type instance
     *
     * @return Mage_Eav_Model_Entity_Type
     */
    public function getEntityType()
    {
        return $this->_getResource()->getEntityType();
    }

    /**
     * Return Entity Type ID
     *
     * @return int
     */
    public function getEntityTypeId()
    {
        $entityTypeId = $this->getData('entity_type_id');
        if (!$entityTypeId) {
            $entityTypeId = $this->getEntityType()->getId();
            $this->setData('entity_type_id', $entityTypeId);
        }
        return $entityTypeId;
    }

    /**
     * Upload Offline Store Image File to Media
     *
     * @param $entityId
     * @param $data Mage Re
     */
    public function saveImage($entityId, $data)
    {
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
            $uploader = new Varien_File_Uploader('image');
            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'png'));
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);
            $result = $uploader->save(Mage::helper('webinseofflinestores')->getImagePath(), $entityId . '.jpg');
            if ($result){
                return $result['file'];
            }
        } else {
            if (isset($data['offlinestore']['delete']) && $data['offlinestore']['delete'] == 1) {
                $imageProperties = explode('.',$data['offlinestore']['value']);
                $ext = end($imageProperties);
                @unlink(Mage::helper('webinseofflinestores')->getImagePath($entityId, $ext));
            }
        }
    }

    /**
     * Retrieve offline store URL
     *
     * @return string
     */
    public function getOfflineStoreUrl()
    {
        return $this->getUrlModel()->getOfflineStoreUrl($this);
    }

    /**
     * Get offline store url model
     *
     * @return Mage_Catalog_Model_Offlinestore_Url
     */
    public function getUrlModel()
    {
        if ($this->_urlModel === null) {
            $this->_urlModel = Mage::getSingleton('webinseofflinestores/factory')->getOfflineStoreUrlInstance();
        }
        return $this->_urlModel;
    }

    /**
     * Retrieve URL instance
     *
     * @return Mage_Core_Model_Url
     */
    public function getUrlInstance()
    {
        if (!self::$_url) {
            self::$_url = Mage::getModel('core/url');
        }
        return self::$_url;
    }

    /**
     * Retrieve offline store id URL
     *
     * @return string
     */
    public function getOfflineStoreIdUrl()
    {
        Varien_Profiler::start('REGULAR: '.__METHOD__);
        $urlKey = $this->formatUrlKey($this->getName());

        $url = $this->getUrlInstance()->getUrl('offlinestores/view', array(
            'offlinestore'=>$urlKey,
            'id'=>$this->getId(),
        ));

        Varien_Profiler::stop('REGULAR: '.__METHOD__);
        return $url;
    }

    /**
     * Retrieve image URL
     *
     * @return string
     */
    public function getImageUrl()
    {
        $url = false;

        if ($image = $this->getImage()) {
            $url = Mage::getBaseUrl('media').'offlinestore/'.$image;
        }
        return $url;
    }

    /**
     * Format URL key from name or defined key
     *
     * @param string $str
     * @return string
     */
    public function formatUrlKey($str)
    {
        $str = Mage::helper('catalog/product_url')->format($str);
        $urlKey = preg_replace('#[^0-9a-z]+#i', '-', $str);
        $urlKey = strtolower($urlKey);
        $urlKey = trim($urlKey, '-');
        return $urlKey;
    }
}