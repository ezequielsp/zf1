<?php

class Admin_IndexController extends Zend_Controller_Action
{
    protected $_table;
    protected $_form;
    
    public function init()
    {
        $req = $this->getRequest();
        
        if ($req->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        }

        $this->module     = $req->getModuleName();
        $this->controller = $req->getControllerName();
        $this->url        = $this->controller . '/' .
                                $req->getActionName();

    }

    public function getTable()
    {
        if (!$this->_table) {
            $this->_table = new Home_Model_DbTable_Tables(
                array(
                    'name' => 'users' //$this->controller
                )
            );
        }
        return $this->_table;
    }

    public function getForm()
    {
        if (!$this->_form) {
            $this->_form = new Admin_Form_Users();
        }
        return $this->_form;
    }

    public function getFormData()
    {
        $data = array(
            'name'     => $this->_form->getValue('name'),
            'active'   => $this->_form->getValue('active'),
            'login'    => $this->_form->getValue('login'),
            'role'     => $this->_form->getValue('role')
        );

        if ($this->_form->getValue('passkey')) {
            $data['password'] = $this->_form->getValue('passkey');
        }

        if ($this->_form->getValue('confirm')) {
            $data['confirm'] = $this->_form->getValue('confirm');
        }

        return $data;
    }

    public function indexAction()
    {
        $table = $this->getTable();
        $page  = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($table->getRowsMapper());

        $paginator->setCurrentPageNumber($page)
                  ->setItemCountPerPage(30);

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial(
            'pagination.phtml'
        );

        $this->view->assign('paginator', $paginator);
        $this->view->title      = $this->module . ' ' . ucfirst($this->controller);
        $this->view->module     = $this->module;
        $this->view->controller = $this->controller;
    }

    public function addAction()
    {
        $form = $this->getForm();
        $form->setAction($this->url)
             ->setMethod('post');

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $data = $this->getFormData();
                $this->getTable()->insert($data);
            } else {
                $form->populate($formData);
            }
        }

        $this->view->form = $form;
    }

    public function editAction()
    {
        $form = $this->getForm();
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id   = (int)$form->getValue('id');
                $data = $this->getFormData();
                $this->getTable()->update($data, 'id = '. $id);
            } else {
                $form->populate($formData);
            }

            $this->_helper->redirector('index');

        } else {
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $table = $this->getTable();
                $data  = $table->find($id)->toArray();
                $form->populate($data[0]);
                $form->setAction($this->url . '/id/' . $id)
                     ->setMethod('post');
            }
        }

        $this->view->form = $form;
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isPost()) {
            $id = $this->getRequest()->getPost('id');
            $this->getTable()->deleteRow($id);

        } else {
            $id = $this->_getParam('id', 0);
            $this->view->row = $this->getTable()->find($id);
        }
    }
}
