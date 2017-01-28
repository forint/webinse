<?php
/**
 * Created by PhpStorm.
 * User: forint
 * Date: 4/8/16
 * Time: 2:21 PM
 */
class DS_News_Model_Category extends Mage_Core_Model_Abstract {

    public function _construct(){
        parent::_construct();
        $this->_init('dsnews/category');
    }

    protected function _afterDelete()
    {
        foreach($this->getNewsCollection() as $news){
            $news->setCategoryId(0)->save();
        }
        return parent::_afterDelete();
    }

    public function getNewsCollection()
    {
        $collection = Mage::getModel('dsnews/news')->getCollection();
        $collection->addFieldToFilter('category_id', $this->getId());
        return $collection;
    }
}