<?php
class App_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract 
{
    
    public function loggedInAs()
    {

        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $username = $auth->getIdentity()->getLogin();
            $logoutUrl = $this->view->url(array('controller'=>'auth', 'action'=>'logout'), null, true);
            return '<p> Welcome ' . $username .  '.</p> <a class="logout_user" href="'.$logoutUrl.'">Logout</a>';
        } 
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        if($controller == 'auth' && $action == 'index') {
            return '';
        }
        $loginUrl = $this->view->url(array('module'=> 'home', 'controller'=>'auth', 'action'=>''));
        return '<a href="'.$loginUrl.'">Login</a>';
    }

}
