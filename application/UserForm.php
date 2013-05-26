<?php
class UserForm extends Zend_Form
{
    function init()
    {
        $this->setMethod('POST');

        $this->addElement('text', 'email', array(
            'label' => 'Email Address',
            'required' => true,
            'validators' => array('EmailAddress')
        ));
        $this->addElement('password', 'password', array(
            'label' => 'Password',
            'required'=>true
        ));
        $this->addElement('password', 'verifypassword', array(
            'label' => 'Verify Password:',
            'required' => true,
            'validators' => array(
                array('identical', true, array('password'))
            )
        ));

        $this->addElement('submit', 'submit', array(
            'label' => 'Submit',
            'order' => 9999
        ));

    }

    public function isValid($data)
    {
        $valid = parent::isValid($data);

        foreach ($this->getElements() as $element) {
            if ($element->hasErrors()) {
                $oldClass = $element->getAttrib('class');
                if (!empty($oldClass)) {
                    $element->setAttrib('class', $oldClass . ' error');
                } else {
                    $element->setAttrib('class', 'error');
                }
            }
        }

        return $valid;
    }
}