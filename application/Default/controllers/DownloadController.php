<?php
class DownloadController extends Zend_Controller_Action
{
    function indexAction()
    {
        $user = bootstrap::getInstance()->getUser();
        if (!$userid = $user['id']) {
            return $this->_redirect('/error/denied');
        }

        Zend_Layout::getMvcInstance()->setLayout('download');
    }

    function csvAction()
    {
        $user = bootstrap::getInstance()->getUser();
        if (!$userid = $user['id']) {
            return $this->_redirect('/error/denied');
        }

        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        header('Content-Description: File Transfer');
        header("Content-Type: application/csv") ;
        header("Content-Disposition: attachment; filename=vehicle-fits-data.csv");
        header("Expires: 0");

        $stream = fopen("php://output", 'w');
        $export = new VF_Import_VehiclesList_CSV_Export();
        echo $export->export($stream);
    }
}