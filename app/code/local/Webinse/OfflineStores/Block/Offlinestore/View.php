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
        $attributes = false;
        $_offlineStore = $this->getCurrentOfflineStore();
        $setId = '14';

        $groupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
            ->setAttributeSetFilter($setId)
            ->setSortOrder()
            ->load();


        foreach ($groupCollection as $group){
            if ($group->getAttributeGroupName() == $groupName){
                $attributes = $_offlineStore->getAttributes($group->getId(), true);
            }
        }

        return $attributes;
    }

}