<?php
class Webinse_OfflineStores_Block_Widget_Catalog extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{
    /**
     * Display products type
     */
    const DISPLAY_TYPE_ALL_PRODUCTS         = 'all_stores';
    const DISPLAY_TYPE_NEW_PRODUCTS         = 'new_products';

    /**
     * Default value whether show pager or not
     */
    const DEFAULT_SHOW_PAGER                = false;

    /**
     * Default value for products per page
     */
    const DEFAULT_PRODUCTS_PER_PAGE         = 20;

    /**
     * Name of request parameter for page number value
     */
    const PAGE_VAR_NAME                     = 'np';

    /**
     * Instance of pager block
     *
     * @var Mage_Catalog_Block_Product_Widget_Html_Pager
     */
    protected $_pager;

    /**
     * Default offline stores amount per row
     *
     * @var int
     */
    protected $_defaultColumnCount = 5;

    /**
     * Initialize block's cache and template settings
     */
    protected function _construct()
    {
        parent::_construct();
    }


    /**
     * Retrieve how much products should be displayed
     *
     * @return int
     */
    public function getOfflineStoresCount()
    {
        if (!$this->hasData('offlinestores_count')) {
            return parent::getProductsCount();
        }
        return $this->getData('offlinestores_count');
    }

    /**
     * Offline Store collection initialize process
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection|Object|Varien_Data_Collection
     */
    protected function _getOfflineStoreCollection()
    {
        /** @var $collection Webinse_OfflineStores_Model_Resource_Offlinestore */
        $collection = Mage::getResourceModel('webinseofflinestores/offlinestore_collection');

        $collection = $collection
            //->addStoreFilter()
            ->addAttributeToSort('created_at', 'desc')
            ->setPageSize($this->getOfflineStoresCount())
            ->setCurPage(1)
        ;
        return $collection;
    }

    /**
     * Prepare collection for display offline stores list
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        $this->setOfflineStoreCollection($this->_getOfflineStoreCollection());
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve loaded offline store collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getLoadedProductCollection()
    {
        return $this->_getOfflineStoreCollection();
    }

    public function getMode(){
        return Mage::helper('webinseofflinestores')->getMode();
    }







    /**
     * Get key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return array_merge(parent::getCacheKeyInfo(), array(
            $this->getDisplayType(),
            $this->getProductsPerPage(),
            intval($this->getRequest()->getParam(self::PAGE_VAR_NAME))
        ));
    }

    /**
     * Retrieve display type for products
     *
     * @return string
     */
    public function getDisplayType()
    {
        if (!$this->hasData('display_type')) {
            $this->setData('display_type', self::DISPLAY_TYPE_ALL_PRODUCTS);
        }
        return $this->getData('display_type');
    }


    /**
     * Retrieve how much products should be displayed
     *
     * @return int
     */
    public function getProductsPerPage()
    {
        if (!$this->hasData('products_per_page')) {
            $this->setData('products_per_page', self::DEFAULT_PRODUCTS_PER_PAGE);
        }
        return $this->getData('products_per_page');
    }

    /**
     * Return flag whether pager need to be shown or not
     *
     * @return bool
     */
    public function showPager()
    {
        if (!$this->hasData('show_pager')) {
            $this->setData('show_pager', self::DEFAULT_SHOW_PAGER);
        }
        return (bool)$this->getData('show_pager');
    }

    /**
     * Render pagination HTML
     *
     * @return string
     */
    public function getPagerHtml()
    {
        if ($this->showPager()) {
            if (!$this->_pager) {
                $this->_pager = $this->getLayout()
                    ->createBlock('catalog/product_widget_html_pager', 'widget.new.product.list.pager');

                $this->_pager->setUseContainer(true)
                    ->setShowAmounts(true)
                    ->setShowPerPage(false)
                    ->setPageVarName(self::PAGE_VAR_NAME)
                    ->setLimit($this->getProductsPerPage())
                    ->setTotalLimit($this->getProductsCount())
                    ->setCollection($this->getProductCollection());
            }
            if ($this->_pager instanceof Mage_Core_Block_Abstract) {
                return $this->_pager->toHtml();
            }
        }
        return '';
    }

}
