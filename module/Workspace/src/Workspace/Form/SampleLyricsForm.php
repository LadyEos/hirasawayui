<?php
namespace Workspace\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\ORM\EntityManager; // \ObjectManager;

class SampleLyricsForm extends Form // implements ObjectManagerAwareInterface
{

    public function __construct($name = null,$sm,$type='lyrics')
    {
        // we want to ignore the name passed
        // $this->setObjectManager($objectManager);
        parent::__construct($name);
        
        $this->add(array(
        		'name' => 'type',
        		'type' => 'hidden',
        		'attributes' => array(
        				'value' => $type
        		)
        ));
        
     
        $this->add(array(
            'name' => 'name', // 'usr_name',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Name'
            )
        ));
        $this->add(array(
        		'name' => 'lyrics', // 'usr_name',
        		'attributes' => array(
        				'type' => 'textarea'
        		),
        		'options' => array(
        				'label' => 'Lyrics'
        		)
        ));
        
        
        $this->add(array(
            'name' => 'description', // 'usr_name',
            'attributes' => array(
                'type' => 'textarea'
            ),
            'options' => array(
                'label' => 'Short description'
            )
        ));
        $this->add(array(
            'name' => 'notes', // 'usr_name',
            'attributes' => array(
                'type' => 'textarea'
            ),
            'options' => array(
                'label' => 'Notes'
            )
        ));
        $this->add(array(
        		'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
        		'name'    => 'genre',
        		'options' => array(
        				'label'          => 'What genre?',
        				'object_manager' => $sm->get('doctrine.entitymanager.orm_default'),
        				'target_class'   => 'Application\Entity\Genres',
        				'property'       => 'name',
        				'empty_option'   => '--- please choose ---'
        		),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Save',
                'id' => 'submitbutton'
            )
        ));
    }

   
}