<?php
class Webinse_OfflineStores_Model_Status extends Mage_Core_Model_Abstract
{
    const STATUS_ENABLED    = 1;
    const STATUS_DISABLED   = 2;

    /**
     * Retrieve option array
     *
     * @return array
     */
    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('webinseofflinestores')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('webinseofflinestores')->__('Disabled')
        );
    }

}
