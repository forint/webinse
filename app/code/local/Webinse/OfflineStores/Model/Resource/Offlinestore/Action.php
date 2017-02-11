<?php
class Webinse_OfflineStores_Model_Resource_Offlinestore_Action extends Webinse_OfflineStores_Model_Resource_Abstract
{
    /**
     * Intialize connection
     *
     */
    protected function _construct()
    {
        $resource = Mage::getSingleton('core/resource');
        $this->setType(Webinse_OfflineStores_Model_Offlinestore::ENTITY)
            ->setConnection(
                $resource->getConnection('catalog_read'),
                $resource->getConnection('catalog_write')
            );
    }

    /**
     * Update attribute values for entity list per store
     *
     * @param array $entityIds
     * @param array $attrData
     * @param int $storeId
     * @return Mage_Catalog_Model_Resource_Product_Action
     */
    public function updateAttributes($entityIds, $attrData, $storeId)
    {
        $this->_attributeValuesToSave   = array();
        $this->_attributeValuesToDelete = array();

        $object = new Varien_Object();
        $object->setIdFieldName('entity_id')
            ->setStoreId($storeId);

        $this->_getWriteAdapter()->beginTransaction();
        try {
            foreach ($attrData as $attrCode => $value) {
                $attribute = $this->getAttribute($attrCode);
                if (!$attribute->getAttributeId()) {
                    continue;
                }
                $i = 0;
                foreach ($entityIds as $entityId) {
                    $i++;
                    $object->setId($entityId);
                    // collect data for save
                    $this->_saveAttributeValue($object, $attribute, $value);
                    // save collected data every 1000 rows
                    if ($i % 1000 == 0) {
                        $this->_processAttributeValues();
                    }
                }

                $this->_processAttributeValues();
            }
            $this->_getWriteAdapter()->commit();
        } catch (Exception $e) {
            $this->_getWriteAdapter()->rollBack();
            throw $e;
        }

        return $this;
    }
}
