<?php
class IndexController extends Zend_Controller_Action
{
    function indexAction()
    {
        Zend_Layout::getMvcInstance()->setLayout('home');
    }
}