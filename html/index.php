<?php
try
{
    chdir('..');
    require 'application/bootstrap.php';
    Zend_Controller_Front::getInstance()->dispatch();
}
catch (Exception $e)
{
    $message = 'Unexpected exception of type [' . get_class($e) .
                '] with message ['. $e->getMessage() .
                '] in ['. $e->getFile() .
                ' line ' . $e->getLine() . ']';
    echo '<html><body><center>'  . $message;
    /*
    if (defined('APPLICATION_ENVIRONMENT') && APPLICATION_ENVIRONMENT != 'production'  )
    {*/
        echo '<br /><br />' . $e->getMessage() . '<br />'  . '<div align="left">Stack Trace:' . '<pre>' . $e->getTraceAsString() . '</pre></div>';
    //}
    echo '</body></html>';
    exit(1);
}

