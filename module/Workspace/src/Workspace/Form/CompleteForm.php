<?php
namespace Workspace\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\ORM\EntityManager; // \ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class CompleteForm extends Form // implements ObjectManagerAwareInterface
{

    public function __construct($name = null, $sm)
    {
        // we want to ignore the name passed
        // $this->setObjectManager($objectManager);
        parent::__construct($name);
        
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Finished',
                'id' => 'submitbutton',
            ),
            'options' =>array(
            	'label'=>'This song is finished i wont work on it anymore',
                
            )
        ));
        
        $this->add(array(
        		'name' => 'cancel',
        		'attributes' => array(
        				'type' => 'submit',
        				'value' => 'Continue',
        				'id' => 'cancelbutton'
        		),
            'options' =>array(
            	'label'=>'No, i want to keep working on this song'
            )
        ));
    }
}