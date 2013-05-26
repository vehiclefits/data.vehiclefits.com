<?php
class UserController extends BaseController
{

    function loginAction()
    {
        $user = bootstrap::getInstance()->getUser();
        if ($user) {
            return $this->_redirect('/');
        }

        $form = new LoginForm;

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
            $db = Zend_Registry::get('db');
            $select = $db->select()
                ->from('vfdata_user')
                ->where('email=?', $this->getParam('email'));

            $user = $select->query()->fetch();

            if (sha1($form->getValue('password')) == $user['password']) {
                $this->updateUserDataIntoSession($user['id']);
                $this->_forward('index', 'Index');
            } else {
                $form->getElement('password')->markAsError()->addError('Invalid password or email not found');
            }
        }

        $this->view->form = $form;
    }

    function dashboardAction()
    {
        $user = bootstrap::getInstance()->getUser();
        if (!$userid = $user['id']) {
            return $this->_redirect('/error/denied');
        }

        $uploaded_files = $this->db()->select()
            ->from('vfdata_uploads')
            ->where('user_id=?',$user['id'])
            ->query()
            ->fetchAll();

        $this->view->user_data = $user;
        $this->view->uploaded_files = $uploaded_files;
    }

    function logoutAction()
    {
        bootstrap::getInstance()->userLogout();
        $this->_redirect('/');
    }

    function editAction()
    {
        $user = bootstrap::getInstance()->getUser();
        if (!$user['id'] || $user['type'] != 'admin') {
            return $this->_redirect('/');
        }

        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from('vfdata_user')
            ->where('id=?', $this->_getParam('id'));
        $userBeingEdited = $select->query()->fetch();

        $form = new UserForm;
        $form->removeElement('password');
        $form->removeElement('verifypassword');

        $this->view->form = $form;

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
            $db = Zend_Registry::get('db');
            $db->update('user', array(
                'email' => $form->getValue('email'),
            ), 'id=' . (int)$this->_getParam('id'));

            $this->view->email = $form->getValue('email');
            $this->view->username = $form->getValue('username');
            $this->_helper->FlashMessenger->addMessage('User Updated');
            return $this->_redirect('/user/manage');
        }

    }

    function registerAction()
    {
        $user = bootstrap::getInstance()->getUser();
        $form = new UserForm;
        $this->view->form = $form;

        if($user['type']!='admin') {
            $form->removeElement('type');
        }

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
            $db = Zend_Registry::get('db');
            $data = array(
                'email' => $form->getValue('email'),
                'password' => sha1($form->getValue('password')),
            );
            $db->insert('vfdata_user', $data);

            $this->view->type = $form->getValue('type');
            $this->view->email = $form->getValue('email');
            $this->view->username = $form->getValue('username');
            return $this->render('success');
        }
    }

}