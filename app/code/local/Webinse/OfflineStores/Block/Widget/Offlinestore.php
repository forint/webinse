<?php
class Webinse_OfflineStores_Block_Widget_Offlinestore
    extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface
{

    /**
     * Default value for products count that will be shown
     */
    const DEFAULT_PRODUCTS_COUNT = 10;

    /**
     * Name of request parameter for page number value
     */
    const PAGE_VAR_NAME                     = 'np';

    /**
     * Offline Store Collection
     *
     * @var Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected $_offlineStoreCollection;

    /**
     * Instance of pager block
     *
     * @var Mage_Catalog_Block_Product_Widget_Html_Pager
     */
    protected $_pager;

    protected $_pageSize;

    protected $_offlineStoresCount;

    protected function _construct()
    {
        $this->setPageSize();
        $count = Mage::getResourceModel('webinseofflinestores/offlinestore_collection')->getSize();
        $this->setOfflineStoresCount($count);
        parent::_construct();

        if ($this->hasData('template')) {
            $this->setTemplate($this->getData('template'));
        }

    }


    public function setPageSize()
    {
        $this->_pageSize = Mage::helper('webinseofflinestores')->getOfflineStoresPerPage();
    }
    public function getPageSize()
    {
        return $this->_pageSize;
    }
    /**
     * Set how much product should be displayed at once.
     *
     * @param $count
     * @return Mage_Catalog_Block_Product_New
     */
     public function setOfflineStoresCount($count)
     {
         $this->_offlineStoresCount = $count;
     }

    /**
     * Get how much products should be displayed at once.
     *
     * @return int
     */
     public function getOfflineStoresCount()
     {
        if (null === $this->_offlineStoresCount) {
            $this->_offlineStoresCount = self::DEFAULT_PRODUCTS_COUNT;
        }
        return $this->_offlineStoresCount;
     }

    /**
     * Retrieve loaded offline store collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getLoadedOfflineStoreCollection()
    {
        return $this->_getOfflineStoreCollection();
    }

    /**
     * Retrieve loaded offline store collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getOfflineStoreCollection()
    {
        if (is_null($this->_offlineStoreCollection)) {
            $this->_offlineStoreCollection= Mage::getResourceModel('webinseofflinestores/offlinestore_collection')
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('image')
                ->addAttributeToSelect('short_description')
                ->addAttributeToSelect('description')
                ->addAttributeToSelect('disposition')
                ->setOrder('disposition', 'ASC')
                ->setPageSize($this->getPageSize())
                ->setCurPage($this->getRequest()->getParam(self::PAGE_VAR_NAME))
                ->load();

        }

        return $this->_offlineStoreCollection;
    }

    /**
     * Get offline store catalog layer model
     *
     * @return Mage_Catalog_Model_Layer
     */
    public function getLayer()
    {
        $layer = Mage::registry('current_layer');
        if ($layer) {
            return $layer;
        }
        return Mage::getSingleton('webinseofflinestores/layer');
    }

    /**
     * Retrieve list toolbar HTML
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    /**
     * Retrieve appearence mode from system configuration
     * @return mixed
     */
    public function getMode(){
        return Mage::helper('webinseofflinestores')->getMode();
    }

    /**
     * Retrieve appearence mode from system configuration
     * @return mixed
     */
    public function getColumnCount(){
        return Mage::getStoreConfig('offlinestores/offlinestoresgroup/offlinestorecolumncount');
    }

    /**
     * Render pagination HTML
     *
     * @return string
     */
    public function getPagerHtml()
    {
        if (!$this->_pager) {
            $this->_pager = $this->getLayout()
                ->createBlock('webinseofflinestores/widget_html_pager', 'offline.store.widget.pager');

            $this->_pager->setUseContainer(true)
                ->setShowAmounts(true)
                ->setShowPerPage(false)
                ->setPageVarName(self::PAGE_VAR_NAME)
                ->setLimit($this->getPageSize())
                ->setTotalLimit($this->getOfflineStoresCount())
                ->setCollection($this->_getOfflineStoreCollection());
        }
        if ($this->_pager instanceof Mage_Core_Block_Abstract) {
            return $this->_pager->toHtml();
        }
        return '';
    }


}