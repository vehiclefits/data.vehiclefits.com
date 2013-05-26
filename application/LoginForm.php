<?php
class LoginForm extends Zend_Form
{
    function init()
    {
        $this->setAction('/user/login');
        $this->setMethod('POST');
        $this->addElement('text', 'email', array(
            'label' => 'Email',
            'required' => true,
        ));
        $this->addElement('password', 'password', array(
            'label' => 'Password:',
            'required' => true,
        ));
        $this->addElement('submit', 'login', array(
            'label' => 'Login'
        ));
    }
}