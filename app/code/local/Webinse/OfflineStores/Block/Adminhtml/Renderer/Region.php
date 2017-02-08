<?php
class Webinse_OfflineStores_Block_Adminhtml_Renderer_Region
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{

    /**
     * Factory instance
     *
     * @var Mage_Core_Model_Abstract
     */
    protected $_factory;

    /**
     * Constructor for Mage_Adminhtml_Block_Customer_Edit_Renderer_Region class
     *
     * @param array $args
     */
    public function __construct(array $args = array())
    {
        $this->_factory = !empty($args['factory']) ? $args['factory'] : Mage::getSingleton('core/factory');
    }

    /**
     * Output the region element and javasctipt that makes it dependent from country element
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $country = $element->getForm()->getElement('country_id');
        if (!is_null($country)) {
            $countryId = $country->getValue();
        } else {
            return $element->getDefaultHtml();
        }

        $regionId = $element->getForm()->getElement('region_id')->getValue();
        $quoteStoreId = $element->getEntityAttribute()->getStoreId();

        $html = '<tbody style="display:none"><tr>';
        $element->setClass('input-text');
        $element->setNoDisplay(true);
        $html .= '<td class="label">' . $element->getLabelHtml() . '</td><td class="value">';


        $html .= '</td></tr></tbody>' . "\n";

        return $html;
    }
}
