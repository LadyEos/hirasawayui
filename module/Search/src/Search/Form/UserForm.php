<?php
namespace Search\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter;

class UserForm extends Form // implements ObjectManagerAwareInterface
{

    public function __construct($name = null,$sm,$selected=null)
    {
        // we want to ignore the name passed
        // $this->setObjectManager($objectManager);
        parent::__construct();
        
        $this->add(array(
            'name' => 'keyword', // 'usr_name',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Search'
            )
        ));
        
        $this->add(array(
        		'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
        		'name'    => 'genre',
        		'attributes' => array(
        				//'multiple' => 'multiple',
        				'class' => 'chosen-select form-control',
        				'id' => 'select-genres',
        		),
        		'options' => array(
        				'label'          => 'Genre',
        				'object_manager' => $sm->get('doctrine.entitymanager.orm_default'),
        				'target_class'   => 'Application\Entity\Genres',
        				'property'       => 'name',
                        'empty_option' => 'Search by genre',
        		        'value' => $selected
        		),
        ));
        
        $this->add(array(
            'name' => 'Search',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Search',
                'id' => 'submitbutton'
            )
        ));
    }

   
}