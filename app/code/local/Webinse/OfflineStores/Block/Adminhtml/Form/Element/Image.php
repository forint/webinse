<?php
class Webinse_OfflineStores_Block_Adminhtml_Form_Element_Image extends Varien_Data_Form_Element_Image
{
    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct()
    {
        $this->setType('file');
        $this->setLabel('Image');
        $this->setName('image');
    }


    /**
     * Retrieve offline store image url
     * @return bool|string
     */
    protected function _getUrl()
    {
        $url = false;
        if ($this->getValue()) {
            $url = Mage::getBaseUrl('media').'offlinestore/'. $this->getValue();
        }
        return $url;
    }

    /**
     * Return html code of hidden element
     *
     * @return string
     */
    protected function _getHiddenInput()
    {
        return '<input type="hidden" name="' . parent::getName() . '[value]" value="' . $this->_getUrl() . '" />';
    }

    /**
     * Return element html code
     *
     * @return string
     */
    public function getElementHtml()
    {
        $html = '';

        if ((string)$this->getValue()) {
            $url = $this->_getUrl();

            if( !preg_match("/^http\:\/\/|https\:\/\//", $url) ) {
                $url = Mage::getBaseUrl('media') . $url;
            }

            $html = '<a href="' . $url . '"'
                . ' onclick="imagePreview(\'' . $this->getHtmlId() . '_image\'); return false;">'
                . '<img src="' . $url . '" id="' . $this->getHtmlId() . '_image" title="' . $this->getValue() . '"'
                . ' alt="' . $this->getValue() . '" height="22" width="22" class="small-image-preview v-middle" />'
                . '</a> ';
        }
        $this->setClass('input-file');
        $html .= $this->getElementHiddenHtml();
        $html .= $this->_getDeleteCheckbox();

        return $html;
    }

    public function getElementHiddenHtml()
    {
        $html = '<input id="'.$this->getHtmlId().'" name="'.$this->getName()
            .'" value="'.$this->_getUrl().'" '.$this->serialize($this->getHtmlAttributes()).'/>'."\n";
        $html.= $this->getAfterElementHtml();
        return $html;
    }
}