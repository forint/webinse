<?php
class Webinse_OfflineStores_Block_Adminhtml_OfflineStores_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected $_isDeleteable = true;

    public function __construct()
    {
        $this->_objectId   = 'offlinestore_id';
        $this->_controller = 'adminhtml_offlinestores';
        $this->_blockGroup = 'webinseofflinestores';//offlinestores_admin

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('customer')->__('Save Offline Store'));
        $this->_updateButton('delete', 'label', Mage::helper('customer')->__('Delete Offline Store'));

        if (Mage::registry('offlinestore')->isReadonly()) {
            $this->_removeButton('save');
            $this->_removeButton('reset');
        }

        if (!Mage::registry('offlinestore')->isDeleteable()) {
            $this->_removeButton('delete');
        }
    }

    public function getCreateOrderUrl()
    {
        return $this->getUrl('*/sales_order_create/start', array('customer_id' => $this->getCustomerId()));
    }

    public function getOfflineStoreId()
    {
        return Mage::registry('offlinestore')->getId();
    }

    public function getHeaderText()
    {
        if (Mage::registry('offlinestore')->getId()) {
            return $this->escapeHtml(Mage::registry('offlinestore')->getName());
        }
        else {
            return Mage::helper('webinseofflinestores')->__('Offline Store Information');
        }
    }

    /**
     * Prepare form html. Add block for configurable product modification interface
     *
     * @return string
     */
    public function getFormHtml()
    {
        $html = parent::getFormHtml();
        return $html;
    }

    public function getValidationUrl()
    {
        return $this->getUrl('*/*/validate', array('_current'=>true));
    }

    protected function _prepareLayout()
    {
        if (!Mage::registry('offlinestore')->isReadonly()) {
            $this->_addButton('save_and_continue', array(
                'label'     => Mage::helper('customer')->__('Save and Continue Edit'),
                'onclick'   => 'saveAndContinueEdit(\''.$this->_getSaveAndContinueUrl().'\')',
                'class'     => 'save'
            ), 10);
        }

        return parent::_prepareLayout();
    }

    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array(
            '_current'  => true,
            'back'      => 'edit',
            'tab'       => '{{tab_id}}'
        ));
    }
}

