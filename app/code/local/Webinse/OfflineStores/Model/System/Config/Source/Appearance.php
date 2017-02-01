<?php
class Webinse_OfflineStores_Model_System_Config_Source_Appearance
{
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label' => 'List'),
            array('value' => 1, 'label' => 'Grid')
        );
    }
}