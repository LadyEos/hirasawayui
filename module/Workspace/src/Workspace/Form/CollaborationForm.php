<?php
namespace Workspace\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\ORM\EntityManager; // \ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class CollaborationForm extends Form // implements ObjectManagerAwareInterface
{

    public function __construct($name = null,$friends)
    {
        // we want to ignore the name passed
        // $this->setObjectManager($objectManager);
        parent::__construct($name);
        
        $this->add(array(
        		'type' => 'Select',
        		'name' => 'friends',
        		'options' => array(
        				'label' => 'Friends',
        				'empty_option' => 'Friends',
        				'value_options' => $friends
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