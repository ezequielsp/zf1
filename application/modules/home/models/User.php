<?php

class Home_Model_User implements Zend_Acl_Role_Interface
{
    private $_userId;
    private $_name;
    private $_roleId;
    private $_login;

    public function getLogin()
    {
        return $this->_login;
    }

    public function setLogin($login)
    {
        $this->_login = (string) $login;
    }
    
    public function setUserId($userId)
    {
        $this->_userId = (int) $userId;
    }
    
    public function getUserId()
    {
        return $this->_userId;
    }
    
    public function setName($name)
    {
        $this->_name = (string) $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getRoleId()
    {
        return $this->_roleId;
    }

    public function setRoleId($roleId)
    {
        $this->_roleId = (string) $roleId;
    }
}
