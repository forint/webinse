<?php
class Webinse_OfflineStores_Block_Adminhtml_OfflineStores_Edit_Tab_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Column headers visibility
     *
     * @var boolean
     */
    protected $_headersVisibility = true;

    /**
     * Filter visibility
     *
     * @var boolean
     */
    protected $_filterVisibility = true;

    public function __construct()
    {
        parent::__construct();
        $this->setId('offlinestore');
        $this->setDefaultSort('increment_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setTemplate('offlinestores/widget/grid.phtml');
        $this->setUseAjax(true);
    }

    public function getOfflineStore()
    {
        return Mage::registry('offlinestore');
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        $store = $this->_getStore();
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('attribute_set_id')
            ->addAttributeToSelect('type_id');

        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $collection->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left');
        }
        if ($store->getId()) {
            //$collection->setStoreId($store->getId());
            $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
            $collection->addStoreFilter($store);
            $collection->joinAttribute(
                'name',
                'catalog_product/name',
                'entity_id',
                null,
                'inner',
                $adminStore
            );
            $collection->joinAttribute(
                'custom_name',
                'catalog_product/name',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'status',
                'catalog_product/status',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'visibility',
                'catalog_product/visibility',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'price',
                'catalog_product/price',
                'entity_id',
                null,
                'left',
                $store->getId()
            );
        }
        else {
            $collection->addAttributeToSelect('price');
            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
        }

        $this->setCollection($collection);

        parent::_prepareCollection();
        $this->getCollection()->addWebsiteNamesToResult();
        return $this;
    }

    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()->joinField('websites',
                    'catalog/product_website',
                    'website_id',
                    'product_id=entity_id',
                    null,
                    'left');
            }
        }
        return parent::_addColumnFilterToCollection($column);
    }

    protected function _prepareColumns()
    {
        if (!$this->getOfflineStore()->getProductsReadonly()) {
            $this->addColumn('in_offlinestore', array(
                'header_css_class' => 'a-center',
                'type'      => 'checkbox',
                'name'      => 'inofflinestore',
                'values'    => $this->_getSelectedProducts(),
                'align'     => 'center',
                'index'     => 'entity_id'
            ));
        }

        $this->addColumn('entity_id',
            array(
                'header'=> Mage::helper('catalog')->__('ID'),
                'width' => '50px',
                'type'  => 'number',
                'index' => 'entity_id',
            ));
        $this->addColumn('name',
            array(
                'header'=> Mage::helper('catalog')->__('Name'),
                'index' => 'name',
            ));

        $store = $this->_getStore();
        if ($store->getId()) {
            $this->addColumn('custom_name',
                array(
                    'header'=> Mage::helper('catalog')->__('Name in %s', $store->getName()),
                    'index' => 'custom_name',
                ));
        }

        $this->addColumn('type',
            array(
                'header'=> Mage::helper('catalog')->__('Type'),
                'width' => '60px',
                'index' => 'type_id',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
            ));

        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
            ->load()
            ->toOptionHash();

        $this->addColumn('set_name',
            array(
                'header'=> Mage::helper('catalog')->__('Attrib. Set Name'),
                'width' => '100px',
                'index' => 'attribute_set_id',
                'type'  => 'options',
                'options' => $sets,
            ));

        $this->addColumn('sku',
            array(
                'header'=> Mage::helper('catalog')->__('SKU'),
                'width' => '80px',
                'index' => 'sku',
            ));

        $store = $this->_getStore();
        $this->addColumn('price',
            array(
                'header'=> Mage::helper('catalog')->__('Price'),
                'type'  => 'price',
                'currency_code' => $store->getBaseCurrency()->getCode(),
                'index' => 'price',
            ));

        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $this->addColumn('qty',
                array(
                    'header'=> Mage::helper('catalog')->__('Qty'),
                    'width' => '100px',
                    'type'  => 'number',
                    'index' => 'qty',
                ));
        }

        $this->addColumn('visibility',
            array(
                'header'=> Mage::helper('catalog')->__('Visibility'),
                'width' => '70px',
                'index' => 'visibility',
                'type'  => 'options',
                'options' => Mage::getModel('catalog/product_visibility')->getOptionArray(),
            ));

        $this->addColumn('status',
            array(
                'header'=> Mage::helper('catalog')->__('Status'),
                'width' => '70px',
                'index' => 'status',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
            ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('websites',
                array(
                    'header'=> Mage::helper('catalog')->__('Websites'),
                    'width' => '100px',
                    'sortable'  => false,
                    'index'     => 'websites',
                    'type'      => 'options',
                    'options'   => Mage::getModel('core/website')->getCollection()->toOptionHash(),
                ));
        }

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

        $this->addColumn('position', array(
            'header'    => Mage::helper('catalog')->__('Position'),
            'width'     => '1',
            'type'      => 'number',
            'index'     => 'position',
            'editable'  => true //!$this->getCategory()->getProductsReadonly()
            //'renderer'  => 'adminhtml/widget_grid_column_renderer_input'
        ));

        if (Mage::helper('catalog')->isModuleEnabled('Mage_Rss')) {
            $this->addRssList('rss/catalog/notifystock', Mage::helper('catalog')->__('Notify Low Stock RSS'));
        }

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array(
                'store'=>$this->getRequest()->getParam('store'),
                'id'=>$row->getId())
        );
    }

    protected function _getSelectedProducts()
    {
        $products = $this->getRequest()->getPost('selected_products');
        if (is_null($products)) {
            $products = $this->getOfflineStore()->getProductsPosition();
            return array_keys($products);
        }
        return $products;
    }

    /**
     * Return visibility of column headers
     *
     * @return boolean
     */
    public function getHeadersVisibility()
    {
        return $this->_headersVisibility;
    }

    /**
     * Set visibility of filter
     *
     * @param boolean $visible
     */
    public function setFilterVisibility($visible=true)
    {
        $this->_filterVisibility = $visible;
    }

    public function getJsObjectName()
    {
        return $this->getId().'JsObject';
    }

    public function getProductsJson()
    {
        $products = $this->getOfflineStore()->getProductsPosition();
        if (!empty($products)) {
            return Mage::helper('core')->jsonEncode($products);
        }
        return '{}';
    }

    /**
     * Retrieve current category instance
     *
     * @return Mage_Catalog_Model_Category
     */
    public function getCategory()
    {
        return Mage::registry('offlinestore');
    }
}