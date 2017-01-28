<?php
/**
 * Created by PhpStorm.
 * User: forint
 * Date: 4/6/16
 * Time: 3:44 PM
 */
class DS_News_Block_News extends Mage_Core_Block_Template {

    public function getNewsCollection()
    {
        $newsCollection = Mage::getModel('dsnews/news')->getCollection();
        $newsCollection->setOrder('created','DESC');
        return $newsCollection;
    }

}