<?php
class Webinse_OfflineStores_Block_Adminhtml_OfflineStores_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('offlineStores');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('webinseofflinestores/offlinestore')->getCollection()
            ->addAttributeToSelect('*');

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id',
            array(
                'header' => 'ID',
                'align' =>'right',
                'width' => '50px',
                'index' => 'entity_id',
            ));
        $this->addColumn('name',
            array(
                'header' => 'Name',
                'align' =>'left',
                'index' => 'name',
            ));
        $this->addColumn('country',
            array(
                'header' => 'Country',
                'align' =>'right',
                'width' => '200px',
                'index' => 'country_id',
                'renderer'  => 'adminhtml/widget_grid_column_renderer_country'
            ));
        $this->addColumn('city',
            array(
                'header' => 'City',
                'align' =>'right',
                'width' => '200px',
                'index' => 'city',
            ));
        $this->addColumn('street',
            array(
                'header' => 'Street',
                'align' =>'right',
                'width' => '200px',
                'index' => 'street',
            ));
        $this->addColumn('disposition',
            array(
                'header' => 'Position',
                'align' =>'right',
                'width' => '50px',
                'index' => 'disposition',
            ));
        $this->addColumn('status',
            array(
                'header' => 'Status',
                'align' =>'right',
                'width' => '100px',
                'index' => 'status',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
                'renderer'  =>  'webinseofflinestores/adminhtml_offlinestores_grid_renderer_status'
            ));

        $this->addColumn('action',
            array(
                'header'    => Mage::helper('catalog')->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('catalog')->__('Edit'),
                        'url'     => array(
                            'base'=>'*/*/edit',
                            'params'=>array('store'=>$this->getRequest()->getParam('store'))
                        ),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
            ));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('offlinestore');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('webinseofflinestores')->__('Delete'),
            'url'  => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('webinseofflinestores')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('webinseofflinestores/system_config_source_status')->getAllOptions();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('webinseofflinestores')->__('Change status'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('webinseofflinestores')->__('Status'),
                    'values' => $statuses
                )
            )
        ));

        //if (Mage::getSingleton('admin/session')->isAllowed('catalog/update_attributes')){
        //    $this->getMassactionBlock()->addItem('attributes', array(
        //        'label' => Mage::helper('catalog')->__('Update Attributes'),
        //        'url'   => $this->getUrl('*/catalog_product_action_attribute/edit', array('_current'=>true))
        //    ));
        //}

        Mage::dispatchEvent('adminhtml_offline_store_grid_prepare_massaction', array('block' => $this));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}