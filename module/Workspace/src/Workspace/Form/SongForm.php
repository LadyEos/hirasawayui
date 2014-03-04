<?php
namespace Workspace\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\ORM\EntityManager; // \ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class SongForm extends Form // implements ObjectManagerAwareInterface
{

    public function __construct($name = null, $sm,$sample=false,$sampleType=null)
    {
        // we want to ignore the name passed
        // $this->setObjectManager($objectManager);
        parent::__construct($name);
        
        $hydrator = new DoctrineHydrator($sm->get('doctrine.entitymanager.orm_default'), '\Application\Entity\Songs');
        $this->setHydrator($hydrator);
        
        if($sample){
            $this->add(array(
            		'name' => 'sample',
            		'type' => 'hidden',
            		'attributes' => array(
            				'value' => $sample
            		)
            ));
        }
        if($sampleType != null){   
            $this->add(array(
            		'name' => 'sampletype',
            		'type' => 'hidden',
            		'attributes' => array(
            				'value' => $sampleType
            		)
            ));
        }
        
        $this->add(array(
            'name' => 'name', // 'usr_name',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Title'
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
                'label' => 'Notes (tidbits and trivia of the song also some random thoughts)'
            )
        ));
        /*$this->add(array(
        		'name' => 'comments', // 'usr_name',
        		'attributes' => array(
        				'type' => 'textarea'
        		),
        		'options' => array(
        				'label' => 'Version Comments'
        		)
        ));*/
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
        
        if(!$sample){
        	$this->add(array(
        			'type' => 'Zend\Form\Element\Checkbox',
        			'name' => 'completed',
        			'options' => array(
        					'label' => 'Mark as complete',
        					'use_hidden_element' => true,
        					'checked_value' => '1',
        					'unchecked_value' => '0'
        			)
        	));
        }
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'To Upload',
                'id' => 'submitbutton'
            )
        ));
    }


}