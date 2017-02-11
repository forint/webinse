<?php
class Webinse_OfflineStores_Block_Adminhtml_OfflineStores_Grid_Renderer_Status
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Render grid row
     *
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $str = '';
        if (is_numeric($row->getStatus())) {
            switch ($row->getStatus()) {
                case Webinse_OfflineStores_Model_Status::STATUS_ENABLED:
                    $str = $this->__('Enabled');
                    break;
                case Webinse_OfflineStores_Model_Status::STATUS_DISABLED:
                    $str = $this->__('Disabled');
                    break;
            }
        }

        if ($str === '') {
            $str = $this->__('Undefined');
        }

        return $this->escapeHtml($str);
     }
}
