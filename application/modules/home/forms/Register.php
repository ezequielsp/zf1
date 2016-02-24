<?php

class Home_Form_Register extends Zend_Form
{
    public function init()
    {
        $this->setName("register");
        $this->setAttrib('class', 'login-form');
        $this->setAction('/auth/register');   
        $this->setMethod('post');

        $this->addElement('text', 'name', array(
            'autocomplete' => "off",
            'class'        => "form-control",
            'placeholder' => "Nome",
            'filters'    => array('StringTrim', 'StripTags'),
            'required'   => true,
            'validators' => array(
                array('StringLength', false, array(2, 50)),
            )
        ));

        $this->addElement('text', 'login', array(
            'autocomplete' => "off",
            'class'        => "form-control",
            'placeholder' => "Login",
            'filters'    => array('StringTrim', 'StripTags'),
            'required'   => true,
            'validators' => array(
                array('StringLength', false, array(4, 50)),
                array('Db_NoRecordExists', 
                        false, array(
                                'schema' => 'eightroom',
                                'table' => 'users',
                                'field' => 'login',
                                'exclude'   => array(
                                    'field' => 'active',
                                    'value' => 2)
                                ,
                                'messages' => array(
                                        'recordFound' => 'Usuário "%value%"  já cadastrado.')
                        )
                    )
            )
        ));

        $this->addElement('password', 'passkey', array(
            'autocomplete' => "off",
            'class'        => "form-control",
            'placeholder' => "Senha",
            'filters'    => array('StringTrim', 'StripTags'),
            'required'   => true,
            'validators' => array(
                array('StringLength', false, array(4, 15)),
            )
        ));

        $this->addElement('password', 'confirm', array(
            'autocomplete' => "off",
            'class'        => "form-control",
            'placeholder' => "Confirmar Senha",
            'filters'    => array('StringTrim', 'StripTags'),
            'required'   => true,
            'validators' => array(
                array('StringLength', false, array(4, 15)),
                array('Identical', false, array('token' => 'passkey')),
            )
        ));

        $this->addElement('submit', 'registerSubmit', array(
            'ignore'   => true,
            'class'    => 'btn btn-green btn-large login-btn',
            'label'         => 'Cadastrar',
        ));
  
    }

}

