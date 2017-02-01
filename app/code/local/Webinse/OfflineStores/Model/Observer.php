<?php
class Webinse_OfflineStores_Model_Observer
{
    public function addButtonTest($observer)
    {
        $container = $observer->getBlock();

        if(null !== $container && $container->getType() == 'webinseofflinestores/adminhtml_offlinestores_edit') {
            $data = array(
                'label'     => 'My button',
                'class'     => 'some-class',
                'onclick'   => 'setLocation(\' '  . Mage::getUrl('*/*', array('param' => 'value')) . '\')',
            );
            $container->addButton('my_button_identifier', $data);
        }

        return $this;
    }
}