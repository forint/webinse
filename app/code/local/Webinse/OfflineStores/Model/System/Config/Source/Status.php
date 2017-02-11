<?php
class Webinse_OfflineStores_Model_System_Config_Source_Status extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        return array(
            array(
                'value' => Webinse_OfflineStores_Model_Status::STATUS_DISABLED,
                'label' => 'Disabled'
            ),
            array(
                'value' => Webinse_OfflineStores_Model_Status::STATUS_ENABLED,
                'label' => 'Enabled'
            )
        );
    }
}