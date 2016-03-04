<?php

class Admin_Form_Users extends Zend_Form
{

    public function init()
    {
        // reset form decorators to remove the 'dl' wrapper
        $this->setDecorators(array('FormElements', 'Form'));

        $decorators = array(
            'ViewHelper',
            'Errors',
            array(
                'Description',
                array('tag' => 'p', 'class' => 'description')
            ),
            array(
                'Label',
                array('separator' => ' ')
            ),
            array(
                array('data' => 'HtmlTag'),
                array('tag' => 'div', 'class' => 'form-group')
            )
        );

        $this->addElement('hidden', 'id', array(
            'class'      => 'form-control',
            'filters'    => array('Int'),
            'validators' => array('NotEmpty'),
            'decorators' => $decorators
        ));

        $this->addElement(
            'text',
            'name',
            array(
                'class'       => 'form-control',
                'decorators'  => $decorators,
                'filters'     => array('StringTrim', 'StripTags'),
                'label'       => 'Nome',
                'placeholder' => "Nome",
                'required'    => true,
                'validators'  => array(
                    array('StringLength', false, array(2, 50)),
                ),
            )
        );

        $this->addElement('text', 'login', array(
            'autocomplete' => "off",
            'class'        => 'form-control',
            'decorators'   => $decorators,
            'filters'      => array('StringTrim', 'StripTags'),
            'label'        => 'Login',
            'placeholder'  => "Login",
            'required'     => true,
            'validators'   => array(
                array('StringLength', false, array(4, 50)),
                array('Db_NoRecordExists',
                        false, array(
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
            'decorators'   => $decorators,
            'filters'      => array('StringTrim', 'StripTags'),
            'label'        => 'Senha',
            'placeholder'  => "Senha",
            //'required'     => true,
            'validators'   => array(
                array('StringLength', false, array(4, 15)),
            )
        ));

        $this->addElement('password', 'confirm', array(
            'autocomplete' => "off",
            'class'        => "form-control",
            'decorators'   => $decorators,
            'filters'      => array('StringTrim', 'StripTags'),
            'label'        => 'Confirmar Senha',
            'placeholder'  => "Confirmar Senha",
            //'required'     => true,
            'validators'   => array(
                array('StringLength', false, array(4, 15)),
                array('Identical', false, array('token' => 'passkey')),
            )
        ));

        //$rolesTable  = new Zend_Db_Table('roles');
        //$rolesRowset = $competitionsTable->fetchAll();
        $roles = array(
            ''       => 'Selecione um perfil',
            'admin'  => 'Admin',
            'desen'  => 'Desen',
            'editor' => 'Editor'
        );
        //foreach ($rolesRowset as $r) {
        //    $roles[$r['id']] = $r['role'];
        //}
        

        $this->addElement('select', 'role', array(
            'class'        => 'form-control',
            'label'        => 'Perfil',
            //'style'      => 'width:250px',
            'multiOptions' => $roles,
            'filters'      => array('Int'),
            'decorators'   => $decorators
        ));
      

        $this->addElement('select', 'active', array(
            'class'        => 'form-control',
            'label'        => 'Perfil',
            //'style'      => 'width:250px',
            'multiOptions' => array(0 => 'Desativado', 1 => 'Ativo'),
            'filters'      => array('Int'),
            'decorators'   => $decorators
        ));

        $this->addDisplayGroup(
            array(
                'name',
                'active',
                'login',
                'role',
                'passkey',
                'confirm'
            ),
            'form_group'
        );
        
        $formGroup = $this->getDisplayGroup('form_group');
        $formGroup->setDecorators(array(
                    'FormElements',
                    'Fieldset',
                    array('HtmlTag',array('tag'=>'div', 'class' => 'col-md-12'))
        ));
    }


}

