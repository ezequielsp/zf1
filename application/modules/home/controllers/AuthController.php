<?php

class AuthController extends Zend_Controller_Action
{
    public function init()
    {
        if($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        }
    }

    public function indexAction()
    {
        // action body
        return $this->redirect("/login");
    }

    public function loginAction()
    {
        $form = new Home_Form_Register();
        $this->view->registerForm = $form;
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost(); 
            $login2 = isset($data['login']) ? $data['login'] : null; 
            $login  = isset($data['bG9naW5zZW5oYQ']) ? $data['bG9naW5zZW5oYQ'] : $login2;
            $psd    = isset($data['passkey']) ? $data['passkey'] : null;
            $pwd    = isset($data['cGFzc3dvcmRzZW5oYQ']) ? $data['cGFzc3dvcmRzZW5oYQ'] : $psd;
            $name   = isset($data['name']) ? $data['name'] : null; 
            
            if(isset($name)) {
                $data = array(
                    'login'      => $login,
                    'name'       => $name,
                    'password'   => SHA1($pwd),
                    'created_at' => time()
                );
                $db = new Home_Model_DbTable_Users();
                $db->save($data);
                try {
                    Home_Model_Auth::login($login, $pwd);
                    //Redireciona para o Controller protegido
                    return $this->_helper->redirector->goToRoute(
                                    array('controller' => 'index'), null, true);
                } catch (Exception $e) {
                    $this->view->message = $e->getMessage();
                }

            } else {
                try {
                    Home_Model_Auth::login($login, $pwd);
                    //Redireciona para o Controller protegido
                    return $this->_helper->redirector->goToRoute(
                                    array('controller' => 'index'), null, true);
                } catch (Exception $e) {
                    $this->view->message = $e->getMessage();
                }
            }

            return FALSE;
        }
    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        return $this->_helper->redirector('index');
    }

    public function registerAction()
    {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->messages = $this->_flashMessenger->getMessages();
        $form = new Home_Form_Register();
        $this->view->registerForm = $form;
        //Verifica se existem dados de POST
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            //Formulário corretamente preenchido?
            if ($form->isValid($dataPost)) {
                $login = $form->getValue('login');
                $name  = $form->getValue('name');
                $pwd   = $form->getValue('passkey');
                $data  = array(
                    'login'      => $login,
                    'name'       => $name,
                    'password'   => SHA1($pwd),
                    'created_at' => date('Y-m-d H:i:s')
                );
                $db = new Home_Model_DbTable_Users();
                $db->save($data);
                try {
                    Home_Model_Auth::login($login, $pwd);
                    //Redireciona para o Controller protegido
                    return $this->_helper->redirector->goToRoute(array(
                        'controller' => 'index', 'action' => 'index'), null, true);
                } catch (Exception $e) {
                    $this->view->message = $e->getMessage();
                }
            } else {
                //Formulário preenchido de forma incorreta
                $form->populate($dataPost);
                $this->view->messages = $form->getErrors();
            }

        }
    }
}

