<?php
class JsController extends Zend_Controller_Action
{
    function init()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    function indexAction()
    {
        error_reporting(E_ALL & ~E_NOTICE);
        //header('Content-Type:application/x-javascript');
        require_once('VF/html/vafAjax.js.include.php');
    }

    function processAction()
    {
        error_reporting(E_ALL & ~E_NOTICE);
        require_once('VF/html/vafAjax.include.php');
    }
}