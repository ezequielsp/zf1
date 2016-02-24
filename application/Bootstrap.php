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

}

