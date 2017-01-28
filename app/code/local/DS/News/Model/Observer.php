<?php
/**
 * Created by PhpStorm.
 * User: forint
 * Date: 4/11/16
 * Time: 1:39 PM
 */
class DS_News_Model_Observer {

    public function my_custom_method($observer){

        $event = $observer->getEvent();

        // getter method to fetch varien object passed from the dispatcher
        $varien_object = $event->getVarienObj();
        $varien_object->setCid('Initialize value from custom function with Observer');

    }
}