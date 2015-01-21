<?php
namespace Workspace\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\ORM\EntityManager; // \ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class AlbumForm extends Form // implements ObjectManagerAwareInterface
{

    public function __construct($name = null, $sm,$options,$selected=null)
    {
        // we want to ignore the name passed
        // $this->setObjectManager($objectManager);
        parent::__construct($name);
        
        $hydrator = new DoctrineHydrator($sm->get('doctrine.entitymanager.orm_default'), '\Application\Entity\Albums');
        $this->setHydrator($hydrator);
        
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Title'
            )
        ));
        
        $this->add(array(
            'name' => 'description',
            'type' => 'text',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Short description'
            )
        ));
        
        if($selected !=null){

            $select = new Element\Select('song');
            $select->setLabel('Choose Album songs');
            $select->setAttributes(array('multiple' => 'multiple',
                    		'class' => 'chosen-select form-control',
                    		'id' => 'select-songs'));
            $select->setValue($selected);
            $select->setValueOptions($options);
            $this->add($select);
        }else{
            $this->add(array(
            		'type' => 'Select',
            		'name' => 'song',
            		'attributes' => array(
            				'multiple' => 'multiple',
            				'class' => 'chosen-select form-control',
            				'id' => 'select-songs'
            		),
            		'options' => array(
            				'label' => 'Choose album songs',
            				'value_options' => $options
            		)
            ));
        }


        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'completed',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Mark as complete',
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0'
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
















