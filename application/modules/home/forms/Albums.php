<?php

class Home_Form_Albums extends Zend_Form
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
            'artist',
            array(
                'class'       => 'form-control',
                'decorators'  => $decorators,
                'placeholder' => "Artist",
                'filters'     => array('StringTrim', 'StripTags'),
                'required'    => true,
                'validators'  => array(
                    array('StringLength', false, array(2, 50)),
                ),
            )
        );

        $this->addElement(
            'text',
            'title',
            array(
                'class'       => 'form-control',
                'decorators'  => $decorators,
                'placeholder' => "Title",
                'filters'     => array('StringTrim', 'StripTags'),
                'required'    => true,
                'validators'  => array(
                    array('StringLength', false, array(2, 50)),
                ),
            )
        );

        $this->addElement('select', 'state', array(
            'class'        => 'form-control js-example-basic-multiple',
            'decorators'   => $decorators,
            'label'        => "Estado",
            'multiple'     => "multiple",
            'multiOptions' => array(
                ''   => "Escolha um Estado",
                'rs' => "Rio Grande do Sul",
                'sc' => "Santa Catarina"),
            'validators'   => array('NotEmpty'),
            'required'     => true,
            'style'        => 'width:300px;'
        ));

        $this->addElement('text', 'data', array(
            'decorators'  => $decorators,
            'name'       => 'jsdatetime',
            'filters'    => array('StringTrim', 'StripTags'),
            'required'   => true,
        ));

        $this->addDisplayGroup(
            array(
                'artist',
                'title',
                'data',
                'state'
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
