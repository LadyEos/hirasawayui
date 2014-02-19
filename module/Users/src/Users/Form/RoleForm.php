<?php
namespace Users\Form;

use Zend\Form\Form;

class RoleForm extends Form //implements ObjectManagerAwareInterface
{
    public function __construct($name = null,$roles)
    {
        // we want to ignore the name passed
        //$this->setObjectManager($objectManager);
        parent::__construct('Role');
        
       
        $this->add(array(
            'type' => 'Select',
            'name' => 'profile_types',
            'options' => array(
                'label' => 'Role',
                'empty_option' => 'Choose a role',
                'value_options' => $roles
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