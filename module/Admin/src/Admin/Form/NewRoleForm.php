<?php
namespace Admin\Form;

use Zend\Form\Form;

class NewRoleForm extends Form //implements ObjectManagerAwareInterface
{
    public function __construct($name = null,$roles=null)
    {
        // we want to ignore the name passed
        //$this->setObjectManager($objectManager);
        parent::__construct('Role');
        
       
        $this->add(array(
            'name' => 'role', // 'usr_name',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Role Name'
            )
        ));
        
        $this->add(array(
        		'type' => 'Select',
        		'name' => 'parentroles',
        		'options' => array(
        				'label' => 'Parent Role',
        				'empty_option' => 'Choose a role',
        				'value_options' => $roles
        		)
        ));
        
        $this->add(array(
        		'name' => 'rolekey', // 'usr_name',
        		'attributes' => array(
        				'type' => 'text',
        				'class' => 'form-control',
        		),
        		'options' => array(
        				'label' => 'Role Key'
        		)
        ));
        
        $this->add(array(
        		'name' => 'height', // 'usr_name',
        		'attributes' => array(
        				'type' => 'Zend\Form\Element\Number'
        		),
        		'options' => array(
        				'label' => 'Hierarchy'
        		)
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Submit',
                'id' => 'submitbutton'
            )
        ));
    }
}