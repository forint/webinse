<?php
class Webinse_OfflineStores_Adminhtml_OfflinestoresController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Initialize Ofline Store Entity
     *
     * @param string $idFieldName
     * @return false|Mage_Core_Model_Abstract
     */
    protected function _initOfflinestore($idFieldName = 'id')
    {
        $this->_title($this->__('Manage Offline Stores'));

        $offlineStoreId = (int) $this->getRequest()->getParam($idFieldName);
        $offlineStore = Mage::getModel('webinseofflinestores/offlinestore');

        if ($offlineStoreId) {
            $offlineStore->load($offlineStoreId);
        }

        Mage::register('offlinestore', $offlineStore);
        return $offlineStore;
    }

    /**
     * Index Action
     */
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
        $this->_initOfflinestore();
        $this->_title($this->__('New Offline Store'));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Save offlie store
     */
    public function saveAction(){

        $storeId        = $this->getRequest()->getParam('store');
        $redirectBack   = $this->getRequest()->getParam('back', false);
        $isEdit         = (int)($this->getRequest()->getParam('id') != null);

        $data = $this->getRequest()->getPost();

        if ($data) {

            $offlineStore = $this->_initOfflineStoreSave();

            if(isset($data['in_offlinestore_products']) && !$offlineStore->getProductsReadonly()){
                $products = Mage::helper('core/string')->parseQueryStr($data['in_offlinestore_products']);
                $offlineStore->setPostedProducts($products);
            }

            try {

                if (isset($data['offlinestore']['delete']) && $data['offlinestore']['delete'] == 1) {
                    $offlineStore->setData('image', '');
                }

                $offlineStore->save();
                $offlineStore->getResource()->_saveOfflineStoreProducts($offlineStore);

                /** @var Webinse_OfflineStores_Model_Offlinestore $offlineStoreId */
                $offlineStoreId = $offlineStore->getId();
                $imageLabel = $offlineStore->saveImage($offlineStoreId, $data);

                if ($imageLabel){
                    $offlineStore->setData('image', $imageLabel);
                    $offlineStore->save();
                }

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
                'id'    => $offlineStoreId,
                '_current'=>true
            ));
        } elseif($this->getRequest()->getParam('popup')) {
            $this->_redirect('*/*/created', array(
                '_current'   => true,
                'id'         => $offlineStoreId,
                'edit'       => $isEdit
            ));
        } else {
            $this->_redirect('*/*/index', array('store'=>$storeId));
        }

    }

    /**
     * Delete offlinestore action
     */
    public function deleteAction()
    {
        $this->_initOfflinestore();
        $offlineStore = Mage::registry('offlinestore');
        if ($offlineStore->getId()) {
            try {
                $offlineStore->load($offlineStore->getId());
                $offlineStore->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The offline store has been deleted.'));
            }
            catch (Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/adminhtml_offlinestores');
    }

    /**
     *
     */
    public function massDeleteAction()
    {
        $offlinestores = $this->getRequest()->getParam('offlinestore', null);

        if (is_array($offlinestores) && sizeof($offlinestores) > 0) {
            try {
                foreach ($offlinestores as $id) {
                    Mage::getModel('webinseofflinestores/offlinestore')->setId($id)->delete();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d offline store have been deleted', sizeof($offlinestores)));
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        } else {
            $this->_getSession()->addError($this->__('Please select offline stores'));
        }
        $this->_redirect('*/*');
    }

    /**
     * Update product(s) status action
     *
     */
    public function massStatusAction()
    {
        $offlineStoreIds = (array)$this->getRequest()->getParam('offlinestore');
        $storeId    = (int)$this->getRequest()->getParam('store', 0);
        $status     = (int)$this->getRequest()->getParam('status');

        try {
            Mage::getSingleton('webinseofflinestores/offlinestore_action')
                ->updateAttributes($offlineStoreIds, array('status' => $status), $storeId);
            Mage::dispatchEvent('offline_store_controller_mass_status', array('offlinestore_ids' => $offlineStoreIds));

            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been updated.', count($offlineStoreIds))
            );
        }
        catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()
                ->addException($e, $this->__('An error occurred while updating the offline store(s) status.'));
        }

        $this->_redirect('*/*/', array('store'=> $storeId));
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

            /* @var $offlineStore Webinse_OfflineStores_Model_Offlinestore */
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
         * Initialize offline store categories
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
            $this->_getSession()->addError(Mage::helper('webinseofflinestores')->__('This offline store no longer exists.'));
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
            strtolower($this->getFullActionName())
        ));

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

    /**
     * WYSIWYG editor action for ajax request
     *
     */
    public function wysiwygAction()
    {
        $elementId = $this->getRequest()->getParam('element_id', md5(microtime()));
        $storeId = $this->getRequest()->getParam('store_id', 0);
        $storeMediaUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $content = $this->getLayout()->createBlock('webinseofflinestores/adminhtml_form_wysiwyg_content', '', array(
            'editor_element_id' => $elementId,
            'store_id'          => $storeId,
            'store_media_url'   => $storeMediaUrl,
        ));
        $this->getResponse()->setBody($content->toHtml());
    }


}