<?php
abstract class BaseController extends Zend_Controller_Action
{
    function updateUserDataIntoSession($id)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()
            ->from('vfdata_user')
            ->where('id=?', $id);
        $user = $select->query()->fetch();
        Zend_Registry::set('user', $user);
        bootstrap::getInstance()->getSession()->user = $user;
    }

    /** @return Zend_Db_Adapter_Abstract */
    function db()
    {
        return Zend_Registry::get('db');
    }
}
