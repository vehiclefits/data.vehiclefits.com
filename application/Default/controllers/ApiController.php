<?php
class ApiController extends BaseController
{
    function uploadAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $postdata = file_get_contents("php://input");
        $this->db()->insert('vfdata_uploads', array(
            'user_id'=>0,
            'uploaded'=>new Zend_Db_Expr('NOW()'),
            'name'=>'',
            'type'=>'',
            'error'=>0,
            'size'=>strlen($postdata),
        ));
        $id = $this->db()->lastInsertId();
        file_put_contents('var/uploads/'.$id, $postdata);

        echo $id;
    }

    function newtokenAction()
    {
        $user = bootstrap::getInstance()->getUser();
        if (!$userid = $user['id']) {
            return $this->_redirect('/error/denied');
        }

        $newToken = sha1(uniqid());

        $this->db()->update('vfdata_user', array(
            'api_token'=>$newToken
        ));
        $this->updateUserDataIntoSession($user['id']);

        $this->_helper->FlashMessenger->addMessage('Generated New API Token');
        return $this->_redirect($this->view->url(array('controller'=>'user','action'=>'dashboard'),null,true));
    }

}