<?php

class IndexController extends Zend_Controller_Action
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
                    'name' => 'albums' //$this->controller
                )
            );
        }
        return $this->_table;
    }

    public function getForm()
    {
        if (!$this->_form) {
            $this->_form = new Home_Form_Albums();
        }
        return $this->_form;
    }

    public function getFormData()
    {
        $data = array(
            'artist' => $this->_form->getValue('artist'),
            'title'  => $this->_form->getValue('title')
        );

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
        $this->view->title      = ucfirst($this->controller);
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
                $data = $this->getFormData($form);

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
