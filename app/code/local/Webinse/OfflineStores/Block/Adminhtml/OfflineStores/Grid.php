<?php
class Webinse_OfflineStores_Block_Adminhtml_OfflineStores_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('offlineStores');
        // $this->setDefaultSort('id_pfay_films');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('webinseofflinestores/offlinestore')->getCollection()
            ->addAttributeToSelect('*');

        /*print_r('<pre>');
        print_r($collection->getSelect()->__toString());
        print_r('</pre>');
        die;*/

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
                'width' => '50px',
                'index' => 'status',
            ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}