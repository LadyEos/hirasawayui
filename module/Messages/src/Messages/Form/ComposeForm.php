<?php
namespace Messages\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter;

class ComposeForm extends Form // implements ObjectManagerAwareInterface
{

    public function __construct($name = null,$sm,$reply =  null)
    {
        // we want to ignore the name passed
        // $this->setObjectManager($objectManager);
        parent::__construct();
        
        $this->add(array(
        		'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
        		'name'    => 'users',
                'attributes' => array(
                    'multiple' => 'multiple',
                    'class' => 'chosen-select-multiple form-control',
                    'id' => 'select-users',
                ),
        		'options' => array(
        				'label'          => 'To',
        				'object_manager' => $sm->get('doctrine.entitymanager.orm_default'),
        				'target_class'   => 'Application\Entity\Users',
        				'property'       => 'username'
        		),
        ));
        
        $this->add(array(
        		'name' => 'subject', // 'usr_name',
        		'attributes' => array(
        				'type' => 'text',
        				'class' => 'form-control'
        		),
        		'options' => array(
        				'label' => 'Subject'
        		)
        ));
        
        $this->add(array(
        		'name' => 'body', // 'usr_name',
        		'attributes' => array(
        				'type' => 'textarea',
        				'class' => 'form-control',
        		),
        		'options' => array(
        				'label' => 'Message'
        		)
        ));
        
        $this->add(array(
    		'name' => 'reply',
    		'type' => 'Hidden',
            'attributes' => array(
                'value' => $reply,
                'id' => 'reply'
                )
        ));
        
        $this->add(array(
            'name' => 'Send',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Send',
                'id' => 'submitbutton'
            )
        ));
    }

   
}