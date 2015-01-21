<?php
namespace Store\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\ORM\EntityManager; // \ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class SubmitForm extends Form // implements ObjectManagerAwareInterface
{

    public function __construct($name = null)
    {
        // we want to ignore the name passed
        // $this->setObjectManager($objectManager);
        parent::__construct($name);
        
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => $name,
                'id' => 'submitbutton'
            )
        ));
    }
}