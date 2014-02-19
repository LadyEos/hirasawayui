<?php
namespace Workspace\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\ORM\EntityManager; // \ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class UploadForm extends Form // implements ObjectManagerAwareInterface
{

    public function __construct($name = null,$sm)
    {
        // we want to ignore the name passed
        // $this->setObjectManager($objectManager);
        parent::__construct($name);
        
        // $hydrator = new DoctrineHydrator($sm->get('doctrine.entitymanager.orm_default'), '\Application\Entity\UserProfiles');
        // $this->setHydrator($hydrator);
        
        $this->add(array(
            'name' => 'name', // 'usr_name',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Name'
            )
        ));
        
        $file = new Element\File('file');
        $file->setLabel('File Input')->setAttributes(array(
        		'id' => 'file'
        ));
        $this->add($file);
        
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
        		'name' => 'comments', // 'usr_name',
        		'attributes' => array(
        				'type' => 'textarea'
        		),
        		'options' => array(
        				'label' => 'Version Comments'
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
        		'type' => 'Zend\Form\Element\Checkbox',
        		'name' => 'completed',
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
                'value' => 'Upload',
                'id' => 'submitbutton'
            )
        ));
        $this->setInputFilter($this->createInputFilter());
    }

    public function createInputFilter()
    {
    	$inputFilter = new InputFilter\InputFilter();
    
    	// File Input
    	$file = new InputFilter\FileInput('file');
    	$file->setRequired(true);
    	$file->getFilterChain()->attachByName('filerenameupload', array(
    			'target' => './public/uploads/works/file.mp3',
    			'overwrite' => false,
    			'use_upload_name' => false,
    			'randomize' => true,
    			'use_upload_extension' => true,
    	));
    	$inputFilter->add($file);
    
    	return $inputFilter;
    }
}