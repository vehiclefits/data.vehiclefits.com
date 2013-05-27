<?php
class ApiController extends BaseController
{
    function indexAction()
    {
        $user = bootstrap::getInstance()->getUser();
        $this->view->api_token =  $user ? $user['api_token'] : 'your_api_token';
    }

    function uploadAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $token = $this->_getParam('token');
        $userID = $this->userIDForToken($token);

        $postdata = file_get_contents("php://input");
        if(!$postdata) {
            echo 0;
            return;
        }
        $this->db()->insert('vfdata_uploads', array(
            'user_id'=>$userID,
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

    function userIDForToken($token)
    {
        if(!$token) {
            return 0;
        }
        return $this->db()->select()
            ->from('vfdata_user', array('id'))
            ->where('api_token=?',$token)
            ->query()
            ->fetchColumn();
    }

}