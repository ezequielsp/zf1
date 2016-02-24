<?php

/**
 * Define as configurações de acesso.
 *
 *
 * @category   App
 * @package    App_Acl_Setup
 * @subpackage Auth
 * @author     Ezequiel Pereira <9892345@gmail.com>
 * @license    http://framework.zend.com/license   BSD License
 * @version    Release: @package_version@
 * @link       http://framework.zend.com/package/PackageName
 * @since      Class available since Release 1.0.0
 * 
 */
class App_Acl_Setup {
    
    
    /**
     * @var Zend_Acl
     * @access protected
     */
    protected $_acl;

    /**
     * @var _acl
     * @access public 
     */
    public function __construct()
    {
        $this->_acl = new Zend_Acl();
        $this->_initialize();
    }    

    protected function _initialize()
    {
        $this->_setupRoles();
        $this->_setupResources();
        $this->_setupPrivileges();
        $this->_saveAcl();
    }

    protected function _setupRoles()
    {     
        $this->_acl->addRole(new Zend_Acl_Role('guest'));
        $this->_acl->addRole(new Zend_Acl_Role('editor'));
        $this->_acl->addRole(new Zend_Acl_Role('desen'));
    }

    protected function _setupResources()
    {    
        $this->_acl->addResource(new Zend_Acl_Resource('home_auth', 
            array('index', 'login', 'logout', 'register')));
        
        $this->_acl->addResource(new Zend_Acl_Resource('home_error',
            array('index', 'error', 'forbidden')));
        
        $this->_acl->addResource(new Zend_Acl_Resource('home_index', 
            array('index', 'add', 'edit', 'delete')));
        
        $this->_acl->addResource(new Zend_Acl_Resource('admin_index', 
            array('index', 'add', 'edit', 'delete')));
    }

    protected function _setupPrivileges() 
    {    
        $this->_acl->allow('desen');
        
        $this->_acl->allow(null, 'home_auth',  
            array('index', 'login', 'logout', 'register'));
        
        $this->_acl->allow(null, 'home_error', 
            array('index', 'error', 'forbidden'));
        
        $this->_acl->allow(null, 'home_index',
            array('index', 'add', 'edit', 'delete'));
        
        $this->_acl->allow('editor', 'admin_index', 
            array('index', 'add', 'edit', 'delete'));

        
        $this->_acl->allow(null);
    }

    protected function _saveAcl()
    {
        $registry = Zend_Registry::getInstance();
        $registry->set('acl', $this->_acl);
    }

}
