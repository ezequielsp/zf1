<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Instância a classe de configurações de permissão de acesso
     * @access protected
     *  
     */
    protected function _initAcl()
    {
        $aclSetup = new App_Acl_Setup();
    }
    
    protected function _initRewrite()
    {
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();

        $config = new Zend_Config_Ini(APPLICATION_PATH . 
                '/configs/routes.ini', 'production');      
        $router->addConfig($config,'routes');
    }

}

