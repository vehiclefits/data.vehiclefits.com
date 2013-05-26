<?php
class UploadController extends Zend_Controller_Action
{
    function indexAction()
    {
        Zend_Registry::set('active_page','upload');

        $user = bootstrap::getInstance()->getUser();
        if (!$userid = $user['id']) {
            return $this->_redirect('/error/denied');
        }

        if(!isset($_FILES['data_file'])) {
            return;
        }

        $this->db()->insert('vfdata_uploads', array(
            'user_id'=>$user['id'],
            'uploaded'=>new Zend_Db_Expr('NOW()'),
            'name'=>$_FILES['data_file']['name'],
            'type'=>$_FILES['data_file']['type'],
            'error'=>$_FILES['data_file']['error'],
            'size'=>$_FILES['data_file']['size'],
        ));
        $id = $this->db()->lastInsertId();
        move_uploaded_file($_FILES['data_file']['tmp_name'], 'var/uploads/'.$id);
    }

    /** @return Zend_Db_Adapter_Abstract */
    function db()
    {
        return Zend_Registry::get('db');
    }

}