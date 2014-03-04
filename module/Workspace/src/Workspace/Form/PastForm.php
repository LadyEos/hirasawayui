<?php
namespace Workspace\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\ORM\EntityManager; // \ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class PastForm extends Form // implements ObjectManagerAwareInterface
{

    public function __construct($name = null, $versions)
    {
        // we want to ignore the name passed
        // $this->setObjectManager($objectManager);
        parent::__construct($name);
        
        
        
         $this->add(array(
            'type' => 'Select',
            'name' => 'version',
            'options' => array(
                'label' => 'Version',
                'empty_option' => 'Past Versions',
                'value_options' => $versions
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