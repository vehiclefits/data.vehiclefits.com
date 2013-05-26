<?php
class ApiController extends BaseController
{
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