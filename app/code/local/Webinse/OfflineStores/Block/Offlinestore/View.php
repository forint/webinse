<?php
class Webinse_OfflineStores_Block_Offlinestore_View extends Mage_Core_Block_Template
{
    /**
     * Retrieve current offline store model object
     *
     * @return Webinse_OfflineStores_Model_Offlinestore
     */
    public function getCurrentOfflineStore()
    {
        if (!$this->hasData('offlinestore')) {
            $this->setData('offlinestore', Mage::registry('offlinestore'));
        }
        return $this->getData('offlinestore');
    }

    public function getProductListHtml()
    {
        return $this->getChildHtml('product_list');
    }

    /**
     * Retrieve Offline Store Address Information
     */
    public function getAddressInformation($groupName)
    {
        $attributes = array();
        $_offlineStore = $this->getCurrentOfflineStore();
        $setId = '14';

        $groupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
            ->setAttributeSetFilter($setId)
            ->setSortOrder()
            ->load();

        /** Filter collection for use region or region_id */
        foreach ($groupCollection as $group){
            if ($group->getAttributeGroupName() == $groupName){
                $_attributes = $_offlineStore->getAttributes($group->getId(), true);
                foreach ($_attributes as $attr){
                    $attributeValue = $attr->getFrontend()->getValue($_offlineStore);

                    if ($attr->getAttributeCode() == 'region' && !empty($attributeValue)){
                        $attributeArray = array(
                            'label' => $attr->getFrontendLabel(),
                            'value' => $attr->getFrontend()->getValue($_offlineStore)
                        );
                    }elseif ($attr->getAttributeCode() == 'region_id' && !empty($attributeValue)){
                        $region = Mage::getModel('directory/region')->load($attr->getFrontend()->getValue($_offlineStore));
                        $attributeArray = array(
                            'label' => $attr->getFrontendLabel(),
                            'value' => $region->getData('name')
                        );
                    }elseif($attr->getAttributeCode() != 'region' && $attr->getAttributeCode() != 'region_id'){
                        $attributeArray = array(
                            'label' => $attr->getFrontendLabel(),
                            'value' => $attr->getFrontend()->getValue($_offlineStore)
                        );
                    }

                    if (isset($attributeArray)){
                        array_push($attributes, $attributeArray);
                        unset($attributeArray);
                    }
                }

            }
        }
        return $attributes;
    }

}