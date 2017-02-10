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
                'value' => 0,
                'label' => 'Disabled'
            ),
            array(
                'value' => 1,
                'label' => 'Enabled'
            )
        );
    }
}