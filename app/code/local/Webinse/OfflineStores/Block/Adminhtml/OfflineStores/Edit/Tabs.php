<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * admin product edit tabs
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Webinse_OfflineStores_Block_Adminhtml_Offlinestores_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    protected $_attributeTabBlock = 'webinseofflinestores/adminhtml_offlinestores_edit_tab_attributes';

    public function __construct()
    {
        parent::__construct();
        $this->setId('offlinestores_info_tabs');
        $this->setDestElementId('offlinestores_edit_form');
        $this->setTitle(Mage::helper('webinseofflinestores')->__('Offline Store Information'));
    }


    protected function _beforeToHtml()
    {
        $offlineStore = $this->getOfflineStore();
        $setId = '14';

        $groupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
            ->setAttributeSetFilter($setId)
            ->setSortOrder()
            ->load();

        foreach ($groupCollection as $group) {
            $attributes = $offlineStore->getAttributes($group->getId(), true);

            // do not add groups without attributes
            foreach ($attributes as $key => $attribute) {
                if( !$attribute->getIsVisible() ) {
                    unset($attributes[$key]);
                }
            }

            if (count($attributes)==0) {
                continue;
            }

            $this->addTab('group_'.$group->getId(), array(
                'label'     => Mage::helper('webinseofflinestores')->__($group->getAttributeGroupName()),
                'content'   => $this->_translateHtml($this->getLayout()->createBlock($this->getAttributeTabBlock(),
                    'adminhtml.offlinestores.edit.tab.attributes')->setGroup($group)
                    ->setGroupAttributes($attributes)
                    ->toHtml()),
            ));
        }

        /*
                if (Mage::registry('current_customer')->getId()) {
                    $this->addTab('view', array(
                        'label'     => Mage::helper('customer')->__('Customer View'),
                        'content'   => $this->getLayout()->createBlock('adminhtml/customer_edit_tab_view')->toHtml(),
                        'active'    => true
                    ));
                }
        */

        $this->addTab('products', array(
            'label'     => Mage::helper('webinseofflinestores')->__('Available Products'),
            'content'   => $this->getLayout()->createBlock(
                'webinseofflinestores/adminhtml_offlinestores_edit_tab_product',
                'offlinestores.product.grid'
            )->toHtml(),
        ));

        $this->_updateActiveTab();
        Varien_Profiler::stop('offlinestores/tabs');
        return parent::_beforeToHtml();
    }

    protected function _updateActiveTab()
    {
        $tabId = $this->getRequest()->getParam('tab');
        if( $tabId ) {
            $tabId = preg_replace("#{$this->getId()}_#", '', $tabId);
            if($tabId) {
                $this->setActiveTab($tabId);
            }
        }
    }

    /**
     * Getting attribute block name for tabs
     *
     * @return string
     */
    public function getAttributeTabBlock()
    {
        if (is_null(Mage::helper('webinseofflinestores')->getAttributeTabBlock())) {
            return $this->_attributeTabBlock;
        }
        return Mage::helper('webinseofflinestores')->getAttributeTabBlock();
    }

    /**
     * Retrive offlinestore object from object if not from registry
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getOfflineStore()
    {
        if (!($this->getData('offlinestore') instanceof Mage_Core_Model_Abstract)) {
            $this->setData('offlinestore', Mage::registry('offlinestore'));
        }
        return $this->getData('offlinestore');
    }

    /**
     * Translate html content
     *
     * @param string $html
     * @return string
     */
    protected function _translateHtml($html)
    {
        Mage::getSingleton('core/translate_inline')->processResponseBody($html);
        return $html;
    }
}
