<?php
/**
 * Created by PhpStorm.
 * User: forint
 * Date: 4/6/16
 * Time: 4:20 PM
 */

class DS_News_Adminhtml_NewsController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {

        $this->loadLayout()->_setActiveMenu('dsnews');
        $this->_addContent($this->getLayout()->createBlock('dsnews/adminhtml_news'));
        $this->renderLayout();

    }

    public function newAction(){
        $this->_forward('edit');
    }

    public function editAction(){

        $id = (int)$this->getRequest()->getParam('id');
        $model = Mage::getModel('dsnews/news');

        if ($data = Mage::getSingleton('adminhtml/session')->getFormData()){
            $model->setData($data)->setId($id);
        }else{
            $model->load($id);
        }
        Mage::register('current_news', $model);

        $this->loadLayout()->_setActiveMenu('dsnews');

        // Require JS & CSS from /skin
        $this->getLayout()->getBlock('head')->addItem('skin_js', 'ds_news/adminhtml/script.js');
        $this->getLayout()->getBlock('head')->addItem('skin_css', 'ds_news/adminhtml/style.css');

        // Require JS & CSS from /js
        // $this->getLayout()->getBlock('head')->addJs('ds_news/adminhtml/scripts.js');
        // $this->getLayout()->getBlock('head')->addItem('js_css', 'ds_news/adminhtml/styles.css');

        $this->_addLeft($this->getLayout()->createBlock('dsnews/adminhtml_news_edit_tabs'));
        $this->_addContent($this->getLayout()->createBlock('dsnews/adminhtml_news_edit'));
        $this->renderLayout();

    }

    public function saveAction(){

        $id = $this->getRequest()->getParam('id');

        if ($data = $this->getRequest()->getPost()){

            try {
                $helper = Mage::helper('dsnews');
                $model = Mage::getModel('dsnews/news');
                // Urgent::
                // при сохранении у модели добавление айди происходит после инициализации данных,
                // т.к. если вначале добавить айди, то при вызове setData айди будет перезатёрт.
                // В итоге при сохранении вместо редактирования текущей записи, будет создана новая
                $model->setData($data)->setId($id);

                if (!$model->getCreated()){
                    $model->setCreated(now());
                }
                $model->save();
                $id = $model->getId();


                if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != ''){

                    $uploader = new Varien_File_Uploader('image');

                    $uploader->setAllowedExtensions(array('jpg','jpeg'));
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);
                    $uploader->save($helper->getImagePath(), $id.'.jpg');

                }else{

                    if (isset($data['image']['delete']) && $data['image']['delete'] == 1){
                        @unlink($helper->getImagePath($id));
                    }

                }

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('News was saved successfully'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                $this->_redirect('*/*/');

            } catch(Exception $e){

                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);

                $this->_redirect('*/*/edit', array(
                    'id' => $this->getRequest()->getParam('id')
                ));

            }
            return;
        }
        Mage::getSingleton('adminhtml/session')->addError($this->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAdction(){

        if ($id = $this->getRequest()->getParam('id')){
            try {

                Mage::getModel('dsnews/news')->setId($id)-delete();
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('News was deleted successfully'));

            } catch (Eception $e){

                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array(
                    'id' => $id
                ));

            }
        }
        $this->_redirect('*/*/');
    }

}