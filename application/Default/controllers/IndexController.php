<?php
class IndexController extends Zend_Controller_Action
{
    function indexAction()
    {
        Zend_Registry::set('active_page','home');
    }
}