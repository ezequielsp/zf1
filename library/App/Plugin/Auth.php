<?php

/**
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
class App_Plugin_Auth extends Zend_Controller_Plugin_Abstract {

    /**
     * @var Zend_Auth
     */
    protected $_auth = null;

    /**
     * @var Zend_Acl
     */
    protected $_acl = null;

    /**
     * @var array
     */
    protected $_notLoggedRoute = array(
        'controller' => 'auth',
        'action'     => 'login',
        'module'     => 'home'
    );

    /**
     * @var array
     */
    protected $_notLogoutRoute = array(
        'controller' => 'auth',
        'action'     => 'logout',
        'module'     => 'home'
    );

    /**
     * @var array
     */
    protected $_forbiddenRoute = array(
        'controller' => 'error',
        'action'     => 'forbidden',
        'module'     => 'home'
    );

    public function __construct()
    {
        $this->_auth = Zend_Auth::getInstance();
        $this->_acl  = Zend_Registry::get('acl');
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module     = $request->getModuleName();
        $controller = $request->getControllerName();
        $action     = $request->getActionName();
        
        $resource = $module . '_' . $controller;
        
        if (!$this->_isAuthorized($resource, $action)) {
            $controller = $this->_forbiddenRoute['controller'];
            $action     = $this->_forbiddenRoute['action'];
            $module     = $this->_forbiddenRoute['module'];
        }
        
        $request->setControllerName($controller);
        $request->setActionName($action);
        $request->setModuleName($module);
    }

    protected function _isAuthorized($resource, $action)
    {
        $user = ($this->_auth->hasIdentity()) ? 
            $this->_auth->getIdentity() :
            'guest';
        
        if (!$this->_acl->has($resource) || 
            !$this->_acl->isAllowed($user, $resource, $action))
            return false;
        return true;
    }

}
