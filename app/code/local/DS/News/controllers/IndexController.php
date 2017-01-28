<?php
/**
 * Created by PhpStorm.
 * User: forint
 * Date: 4/6/16
 * Time: 1:25 PM
 */
class DS_News_IndexController extends Mage_Core_Controller_Front_Action {

    private function dispatchCustomEvent()
    {

        $event_data_array = array(
            'cid'=>'123'
        );

        $varien_object = new Varien_Object($event_data_array);
        Mage::dispatchEvent('my_custom_event', array('varien_obj'=>$varien_object));


        echo $varien_object->getCid();
    }

    public function indexActionPrevious(){

        $resource = Mage::getSingleton('core/resource');
        $connection = $resource->getConnection('core_read');

        $table = $resource->getTableName('dsnews/table_news');
        //var_dump($table);die();
        $query = $connection->select()->from($table, array('news_id','title','content','created'))->order('created ASC');
        $news = $connection->fetchAll($query);

        Mage::register('news', $news);
        $this->loadLayout();
        $this->renderLayout();

    }

    public function indexAction()
    {
        die('index');
        $this->dispatchCustomEvent();
        $this->loadLayout();
        $this->getLayout()->getBlock('root')->setTemplate('page/2columns-left.phtml');  //changes the root template
        $this->renderLayout();

    }

    public function viewAction(){

        die('view');
        $newsId = Mage::app()->getRequest()->getParam('id',0);
        $news = Mage::getModel('dsnews/news')->load($newsId);

        if ($news->getId() > 0){

            $this->loadLayout();
            $this->getLayout()->getBlock('news.content')->assign(array("newsItem"=>$news));
            $this->renderLayout();

        }else{
            $this->_forward('noRoute');
        }
    }


}