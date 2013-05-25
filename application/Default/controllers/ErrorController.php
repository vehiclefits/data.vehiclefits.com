<?php
/**
 * Error controller, handles user denied & 404s and other errors
 *
 * @package default
 * @subpackage controllers
 */
class ErrorController extends Zend_Controller_Action
{
    function errorAction()
    {
        // Grab the error object from the request
        $errors = $this->_getParam('error_handler');

        // $errors will be an object set as a parameter of the request object,
        // type is a property
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error 
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }

        // pass the environment to the view script so we can conditionally
        // display more/less information
        $this->view->env = $this->getInvokeArg('env');

        // pass the actual exception object to the view
        $this->view->exception = $errors->exception;

        // pass the request to the view
        $this->view->request = $errors->request;
        $this->render('error', null, true);

    }

    function deniedAction()
    {
        /*if( !$this->getUser()->isAuthenticated() )
        {
           return $this->_forward( 'index', 'Login', 'User' );
        } */
        $this->view->user = $this->getUser();
    }

    function notfoundAction()
    {
        $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
    }

} 