<?php

class Admin_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $req = $this->getRequest();
        
        if ($req->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        }

        $this->url = $req->getControllerName() . '/' .
                     $req->getActionName();
    }

    public function indexAction()
    {
        // action body
    }


}

