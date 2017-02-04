<?php
class Webinse_OfflineStores_Model_Resource_Offlinestore extends Mage_Eav_Model_Entity_Abstract
{

    /**
     * Entity type configuration
     *
     * @var Mage_Eav_Model_Entity_Type
     */
    protected $_type;

    /**
     * Offline store products table name
     *
     * @var string
     */
    protected $_offlinestoreProductTable;

    /**
     * Initialize connection with eav data model
     */
    public function _construct()
    {
        $this->setType('offlinestore');
        $this->setConnection('offlinestores_read', 'offlinestores_write');
        $this->_offlinestoreProductTable = $this->getTable('webinseofflinestores/offlinestores_product');
    }

    /**
     * Retrieve customer entity default attributes
     *
     * @return array
     */
    protected function _getDefaultAttributes()
    {
        return array(
            'entity_type_id',
            'attribute_set_id',
            'created_at',
            'updated_at',
            'increment_id',
            'store_id',
            'website_id'
        );
    }

    /**
     * Save category products relation
     *
     * @param Mage_Catalog_Model_Category $category
     * @return Mage_Catalog_Model_Resource_Category
     */
    public function _saveOfflineStoreProducts($offlinestore)
    {
        $offlinestore->setIsChangedProductList(false);
        $id = $offlinestore->getEntityId();

        /**
         * new category-product relationships
         */
        $products = $offlinestore->getPostedProducts();
        /**
         * Example re-save category
         */
        if ($products === null) {
            return $this;
        }

        /**
         * old category-product relationships
         */
        $oldProducts = $offlinestore->getProductsPosition();
        if (!$oldProducts){
            $insert = array_diff_key($products, $oldProducts);
        }else{
            $insert = $products;
        }
        $delete = array_diff_key($oldProducts, $products);

        /**
         * Find product ids which are presented in both arrays
         * and saved before (check $oldProducts array)
         */
        $update = array_intersect_key($products, $oldProducts);
        $update = array_diff_assoc($update, $oldProducts);

        $adapter = $this->_getWriteAdapter();

        /**
         * Delete products from offline store
         */
        if (!empty($delete)) {
            $cond = array(
                'product_id IN(?)' => array_keys($delete),
                'offlinestore_id=?' => $id
            );
            $adapter->delete($this->_offlinestoreProductTable, $cond);
        }

        /**
         * Add products to offline store
         */
        if (!empty($insert)) {
            $data = array();
            foreach ($insert as $productId => $position) {
                $data[] = array(
                    'offlinestore_id' => (int)$id,
                    'product_id'  => (int)$productId,
                    'position'    => (int)$position
                );
            }
            $adapter->insertMultiple($this->_offlinestoreProductTable, $data);
        }

        /**
         * Update product positions in offline store
         */
        if (!empty($update)) {
            foreach ($update as $productId => $position) {
                $where = array(
                    'offlinestore_id = ?'=> (int)$id,
                    'product_id = ?' => (int)$productId
                );
                $bind  = array('position' => (int)$position);
                $adapter->update($this->_offlinestoreProductTable, $bind, $where);
            }
        }

        if (!empty($insert) || !empty($delete)) {
            $productIds = array_unique(array_merge(array_keys($insert), array_keys($delete)));
            Mage::dispatchEvent('offlinestore_change_products', array(
                'offlinestore'      => $offlinestore,
                'product_ids'       => $productIds
            ));
        }

        if (!empty($insert) || !empty($update) || !empty($delete)) {
            $offlinestore->setIsChangedProductList(true);

            /**
             * Setting affected products to category for third party engine index refresh
             */
            $productIds = array_keys($insert + $delete + $update);
            $offlinestore->setAffectedProductIds($productIds);
        }
        return $this;
    }

    /**
     * Check customer by id
     *
     * @param int $customerId
     * @return bool
     */
    public function checkCustomerId($customerId)
    {
        $adapter = $this->_getReadAdapter();
        $bind    = array('entity_id' => (int)$customerId);
        $select  = $adapter->select()
            ->from($this->getTable('customer/entity'), 'entity_id')
            ->where('entity_id = :entity_id')
            ->limit(1);

        $result = $adapter->fetchOne($select, $bind);
        if ($result) {
            return true;
        }
        return false;
    }

    /**
     * Get customer website id
     *
     * @param int $customerId
     * @return int
     */
    public function getWebsiteId($customerId)
    {
        $adapter = $this->_getReadAdapter();
        $bind    = array('entity_id' => (int)$customerId);
        $select  = $adapter->select()
            ->from($this->getTable('customer/entity'), 'website_id')
            ->where('entity_id = :entity_id');

        return $adapter->fetchOne($select, $bind);
    }

    /**
     * Custom setter of increment ID if its needed
     *
     * @param Varien_Object $object
     * @return Mage_Customer_Model_Resource_Customer
     */
    public function setNewIncrementId(Varien_Object $object)
    {
        if (Mage::getStoreConfig(Mage_Customer_Model_Customer::XML_PATH_GENERATE_HUMAN_FRIENDLY_ID)) {
            parent::setNewIncrementId($object);
        }
        return $this;
    }

    /**
     * Change reset password link token
     *
     * Stores new reset password link token and its creation time
     *
     * @param Mage_Customer_Model_Customer $newResetPasswordLinkToken
     * @param string $newResetPasswordLinkToken
     * @return Mage_Customer_Model_Resource_Customer
     */
    public function changeResetPasswordLinkToken(Mage_Customer_Model_Customer $customer, $newResetPasswordLinkToken) {
        if (is_string($newResetPasswordLinkToken) && !empty($newResetPasswordLinkToken)) {
            $customer->setRpToken($newResetPasswordLinkToken);
            $currentDate = Varien_Date::now();
            $customer->setRpTokenCreatedAt($currentDate);
            $this->saveAttribute($customer, 'rp_token');
            $this->saveAttribute($customer, 'rp_token_created_at');
        }
        return $this;
    }

    /**
     * Get positions of associated to category products
     *
     * @param Mage_Catalog_Model_Category $category
     * @return array
     */
    public function getProductsPosition($offlineStore)
    {
        $select = $this->_getWriteAdapter()->select()
            ->from($this->_offlinestoreProductTable, array('product_id', 'position'))
            ->where('offlinestore_id = :offlinestore_id');

        $bind = array('offlinestore_id' => (int)$offlineStore->getEntityId());

        return $this->_getWriteAdapter()->fetchPairs($select, $bind);
    }
}
