<?php
/**
 * Created by PhpStorm.
 * User: forint
 * Date: 4/6/16
 * Time: 3:13 PM
 */
class DS_News_Model_Resource_News extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct(){
        $this->_init('dsnews/table_news','news_id');
    }

}