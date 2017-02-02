<?php
class Webinse_OfflineStores_Adminhtml_OfflinestoresController extends Mage_Adminhtml_Controller_Action
{
    protected function _initOfflinestore($idFieldName = 'id')
    {
        $this->_title($this->__('Manage Offline Stores'))->_title($this->__('Manage Offline Stores'));

        $offlineStoreId = (int) $this->getRequest()->getParam($idFieldName);
        $offlineStore = Mage::getModel('webinseofflinestores/offlinestore');

        if ($offlineStoreId) {
            $offlineStore->load($offlineStoreId);
        }

        Mage::register('offlinestore', $offlineStore);
        return $offlineStore;
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('webinseofflinestores');
        $this->renderLayout();
    }

    /**
     * Create new offline store entity
     */
    public function newAction(){

        $offlineStore = $this->_initOfflinestore();
        /*

        $this->_title($this->__('New Product'));

        Mage::dispatchEvent('catalog_product_new_action', array('product' => $product));

        if ($this->getRequest()->getParam('popup')) {
            $this->loadLayout('popup');
        } else {
            $_additionalLayoutPart = '';
            if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE
                && !($product->getTypeInstance()->getUsedProductAttributeIds()))
            {
                $_additionalLayoutPart = '_new';
            }
            $this->loadLayout(array(
                'default',
                strtolower($this->getFullActionName()),
                'adminhtml_catalog_product_'.$product->getTypeId() . $_additionalLayoutPart
            ));
            $this->_setActiveMenu('catalog/products');
        }

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $block = $this->getLayout()->getBlock('catalog.wysiwyg.js');
        if ($block) {
            $block->setStoreId($product->getStoreId());
        }*/
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Display available products
     * With opportunity bind them to offline store entity
     */
    public function availableAction()
    {
        $this->_initOfflinestore();
        $this->loadLayout();
        $blocks = $this->getLayout()->getAllBlocks();
        foreach($blocks as $block){
            echo $block->getNameInLayout();
            echo PHP_EOL;
        }
        var_dump($this->getLayout()->getBlock('offlinestores.product.edit.tab.available'));die;
        $this->getLayout()->getBlock('catalog.product.edit.tab.available')
            ->setProductsRelated($this->getRequest()->getPost('products_related', null));
        $this->renderLayout();
    }

    /**
     * Save method
     */
    public function saveAction(){

        $storeId        = $this->getRequest()->getParam('store');
        $redirectBack   = $this->getRequest()->getParam('back', false);
        $productId      = $this->getRequest()->getParam('id');
        $isEdit         = (int)($this->getRequest()->getParam('id') != null);

        $data = $this->getRequest()->getPost();
        if ($data) {
            $offlineStore = $this->_initOfflineStoreSave();

            try {
                $offlineStore->save();

                $offlineStoreId = $offlineStore->getId();

                if (isset($data['copy_to_stores'])) {
                    $this->_copyAttributesBetweenStores($data['copy_to_stores'], $offlineStoreId);
                }

                $this->_getSession()->addSuccess($this->__('The offline store has been saved.'));
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage())
                    ->setProductData($data);
                $redirectBack = true;
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
                $redirectBack = true;
            }
        }

        if ($redirectBack) {
            $this->_redirect('*/*/edit', array(
                'id'    => $productId,
                '_current'=>true
            ));
        } elseif($this->getRequest()->getParam('popup')) {
            $this->_redirect('*/*/created', array(
                '_current'   => true,
                'id'         => $productId,
                'edit'       => $isEdit
            ));
        } else {
            $this->_redirect('*/*/', array('store'=>$storeId));
        }

    }

    /**
     * Validate method
     */
    public function validateAction()
    {
        $response = new Varien_Object();
        $response->setError(false);

        try {
            $offlineStoreData = $this->getRequest()->getPost('offlinestore');

            /* @var $offlineStore Mage_Catalog_Model_Product */
            $offlineStore = Mage::getModel('webinseofflinestores/offlinestore');
            $offlineStore->setData('_edit_mode', true);
            if ($storeId = $this->getRequest()->getParam('store')) {
                $offlineStore->setStoreId($storeId);
            }
            if ($setId = $this->getRequest()->getParam('set')) {
                $offlineStore->setAttributeSetId($setId);
            }
            if ($typeId = $this->getRequest()->getParam('type')) {
                $offlineStore->setTypeId($typeId);
            }
            if ($offlineStoreId = $this->getRequest()->getParam('id')) {
                $offlineStore->load($offlineStoreId);
            }

            $dateFields = array();
            $attributes = $offlineStore->getAttributes();
            foreach ($attributes as $attrKey => $attribute) {
                if ($attribute->getBackend()->getType() == 'datetime') {
                    if (array_key_exists($attrKey, $offlineStoreData) && $offlineStoreData[$attrKey] != ''){
                        $dateFields[] = $attrKey;
                    }
                }
            }
            $offlineStoreData = $this->_filterDates($offlineStoreData, $dateFields);
            $offlineStore->addData($offlineStoreData);
            $offlineStore->validate();

        }
        catch (Mage_Eav_Model_Entity_Attribute_Exception $e) {
            $response->setError(true);
            $response->setAttribute($e->getAttributeCode());
            $response->setMessage($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $response->setError(true);
            $response->setMessage($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_initLayoutMessages('adminhtml/session');
            $response->setError(true);
            $response->setMessage($this->getLayout()->getMessagesBlock()->getGroupedHtml());
        }

        $this->getResponse()->setBody($response->toJson());
    }

    /**
     * Settings Action
     * Redirect to Current Module System Preferences
     */
    public function settingsAction()
    {
        $this->_redirect('adminhtml/system_config/edit',
            array(
                'section' => 'offlinestores'
            )
        );
    }
    
    /**
     * Initialize product before saving
     */
    protected function _initOfflineStoreSave()
    {
        $offlinestore     = $this->_initOfflinestore();
        $offlinestoreData = $this->getRequest()->getPost('offlinestore');

        /**
         * Websites
         */
        if (!isset($productData['website_ids'])) {
            $productData['website_ids'] = array();
        }

        $offlinestore->addData($offlinestoreData);


        if (Mage::app()->isSingleStoreMode()) {
            $offlinestore->setWebsiteIds(array(Mage::app()->getStore(true)->getWebsite()->getId()));
        }

        /**
         * Create Permanent Redirect for old URL key
         */
        if ($offlinestore->getId() && isset($offlinestoreData['url_key_create_redirect']))
            // && $product->getOrigData('url_key') != $product->getData('url_key')
        {
            $offlinestore->setData('save_rewrites_history', (bool)$productData['url_key_create_redirect']);
        }

        /**
         * Check "Use Default Value" checkboxes values
         */
        if ($useDefaults = $this->getRequest()->getPost('use_default')) {
            foreach ($useDefaults as $attributeCode) {
                $offlinestore->setData($attributeCode, false);
            }
        }

        /**
         * Init product links data (related, upsell, crosssel)
         */
        $links = $this->getRequest()->getPost('links');
        if (isset($links['related']) && !$offlinestore->getRelatedReadonly()) {
            $offlinestore->setRelatedLinkData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['related']));
        }

        /**
         * Initialize product categories
         */
        $productIds = $this->getRequest()->getPost('product_ids');
        if (null !== $productIds) {
            if (empty($productIds)) {
                $productIds = array();
            }
            $offlinestore->setProductIds($productIds);
        }

        Mage::dispatchEvent(
            'offline_store_prepare_save',
            array('offlinestore' => $offlinestore, 'request' => $this->getRequest())
        );

        return $offlinestore;
    }

    /**
     * Set assigned product IDs array to offline store
     *
     * @param array|string $ids
     * @return Mage_Catalog_Model_Product
     */
    public function setProductsIds($ids)
    {
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        } elseif (!is_array($ids)) {
            Mage::throwException(Mage::helper('catalog')->__('Invalid product IDs.'));
        }
        foreach ($ids as $i => $v) {
            if (empty($v)) {
                unset($ids[$i]);
            }
        }

        $this->setData('product_ids', $ids);
        return $this;
    }

    /**
     * Offline store edit form
     */
    public function editAction()
    {
        $offlineStoreId  = (int) $this->getRequest()->getParam('id');
        $offlineStore = $this->_initOfflinestore();

        if ($offlineStoreId && !$offlineStore->getId()) {
            $this->_getSession()->addError(Mage::helper('catalog')->__('This product no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }

        $this->_title($offlineStore->getName());

        Mage::dispatchEvent('offline_store_edit_action', array('offlineStore' => $offlineStore));

        $_additionalLayoutPart = '';
        if ($offlineStore->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE
            && !($offlineStore->getTypeInstance()->getUsedProductAttributeIds()))
        {
            $_additionalLayoutPart = '_new';
        }

        $this->loadLayout(array(
            'default',
            strtolower($this->getFullActionName()),
            'adminhtml_catalog_product_'.$offlineStore->getTypeId() . $_additionalLayoutPart
        ));
        Mage::Log('crazy',null,'crazy.log');
        if (!Mage::app()->isSingleStoreMode() && ($switchBlock = $this->getLayout()->getBlock('store_switcher'))) {
            $switchBlock->setDefaultStoreName($this->__('Default Values'))
                ->setWebsiteIds($offlineStore->getWebsiteIds())
                ->setSwitchUrl(
                    $this->getUrl('*/*/*', array('_current'=>true, 'active_tab'=>null, 'tab' => null, 'store'=>null))
                );
        }

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $block = $this->getLayout()->getBlock('catalog.wysiwyg.js');
        if ($block) {
            $block->setStoreId($offlineStore->getStoreId());
        }

        $this->renderLayout();
    }

    /**
     * Get model by path
     *
     * @param string $path
     * @param array|null $arguments
     * @return false|Mage_Core_Model_Abstract
     */
    public function _getModel($path, $arguments = array())
    {
        return Mage::getModel($path, $arguments);
    }

}