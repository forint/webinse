<?php
/**
 * Created by PhpStorm.
 * User: forint
 * Date: 4/8/16
 * Time: 3:50 PM
 */
class DS_News_Block_Adminhtml_Category_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    protected function _prepareCollection()
    {

        $collection = Mage::getModel('dsnews/category')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $helper = Mage::helper('dsnews');

        $this->addColumn('category_id', array(
            'header' => $helper->__('Category ID'),
            'index' => 'category_id',
            'width'  => '30px'
        ));

        $this->addColumn('title', array(
            'header' => $helper->__('Title'),
            'index' => 'title',
            'type' => 'text',
        ));

        return parent::_prepareColumns(); // TODO: Change the autogenerated stub
    }

    protected function _prepareMassaction()
    {
        // Устанавливаем id-поле
        $this->setMassactionIdField('categories_id');
        // Устанавливаем название параметра, которое будет использоваться для получения массива id-полей
        $this->getMassactionBlock()->setFormFieldName('categories');

        // Добавляем опции в список масовых операций
        // id операции, название и ссылка, куда отправлять выбранные записи
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => $this->__('Delete'),
            'url'   => $this->getUrl('*/*/massDelete'),
        ));
        return $this;
    }

    /*
     * Method to initiliaze reference for each row of Data Grid
     * Response to the user clicking on a line
     */
    public function getRowUrl($model){

        return $this->getUrl('*/*/edit', array(
            'id' => $model->getId(),
        ));

    }

}