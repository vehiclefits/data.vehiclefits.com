<?php
class UploadController extends Zend_Controller_Action
{
    function indexAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout(););

        $user = bootstrap::getInstance()->getUser();

        if(!isset($_FILES['data_file'])) {
            return;
        }

        $this->db()->insert('vfdata_uploads', array(
            'user_id'=>$user == false ? 0 : $user['id'],
            'uploaded'=>new Zend_Db_Expr('NOW()'),
            'name'=>$_FILES['data_file']['name'],
            'type'=>$_FILES['data_file']['type'],
            'error'=>$_FILES['data_file']['error'],
            'size'=>$_FILES['data_file']['size'],
        ));
        $id = $this->db()->lastInsertId();
        move_uploaded_file($_FILES['data_file']['tmp_name'], 'var/uploads/'.$id);
        $this->_helper->FlashMessenger->addMessage('File Uploaded');
        return $this->_redirect($_SERVER['HTTP_REFERER']);
    }

    /** @return Zend_Db_Adapter_Abstract */
    function db()
    {
        return Zend_Registry::get('db');
    }

}