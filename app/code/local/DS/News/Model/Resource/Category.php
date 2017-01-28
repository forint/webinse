<?php
/**
 * Created by PhpStorm.
 * User: forint
 * Date: 4/8/16
 * Time: 4:49 PM
 */
class DS_News_Model_Resource_Category extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct(){
        $this->_init('dsnews/table_categories','category_id');
    }

    /*protected function _afterDelete()
    {
        foreach($this->getNewsCollection() as $news){
            $news->setCategoryId(0)->save();
        }
        return parent::_afterDelete();
    }*/
}