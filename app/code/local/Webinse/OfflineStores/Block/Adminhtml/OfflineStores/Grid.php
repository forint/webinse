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
        $collection = Mage::getModel('webinseofflinestores/offlinestores')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
/*        •	Id
•	Name
•	Country
•	City
•	Street
•	Position
•	Status*/

        $this->addColumn('offlinestore_id',
            array(
                'header' => 'ID',
                'align' =>'right',
                'width' => '50px',
                'index' => 'offlinestore_id',
            ));

        $this->addColumn('name',
            array(
                'header' => 'name',
                'align' =>'left',
                'index' => 'name',
            ));
        $this->addColumn('Country',
            array(
                'header' => 'Country',
                'align' =>'left',
                'index' => 'Country',
            ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}