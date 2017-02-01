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
        // var_dump(get_parent_class(Mage::getModel('webinseofflinestores/offlinestores')));die;
        /*print_r('<pre>');
        print_r(get_class_methods(Mage::getModel('webinseofflinestores/offlinestores')));
        print_r('</pre>');
        die;*/
        $collection = Mage::getModel('webinseofflinestores/offlinestore')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id',
            array(
                'header' => 'ID',
                'align' =>'right',
                'width' => '50px',
                'index' => 'id',
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
                'index' => 'country',
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
        $this->addColumn('position',
            array(
                'header' => 'Position',
                'align' =>'right',
                'width' => '50px',
                'index' => 'position',
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