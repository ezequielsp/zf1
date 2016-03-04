<?php

class Home_Model_Auth
{
    public static function login($login, $senha)
    {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter('db');
        
        // Inicia o adaptador Zend_Auth para banco de dados
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        $authAdapter->setTableName('users')
                    ->setIdentityColumn('login')
                    ->setCredentialColumn('password')
                    ->setCredentialTreatment('SHA1(?) AND active=1');
        
        // Define os dados para processar o login
        $authAdapter->setIdentity($login)
                    ->setCredential($senha);

        // Efetua o login
        $auth   = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);

        // Verifica se o login foi efetuado com sucesso
        if ($result->isValid()) {
            // Recupera o objeto do usuário, sem a senha
            $info = $authAdapter->getResultRowObject(null, 'password');
            $usuario = new Home_Model_User();
            $usuario->setUserId($info->id);
            $usuario->setName($info->name);
            $usuario->setLogin($info->login);
            $usuario->setRoleId($info->role);

            $storage = $auth->getStorage();
            $storage->write($usuario);
            return true;
        }
        throw new Exception('<div class="alert alert-danger">Nome de usu&aacute;rio ou senha inv&aacute;lida</div>');
    }
}

