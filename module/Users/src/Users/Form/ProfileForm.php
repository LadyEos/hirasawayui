<?php
namespace Users\Form;

use Zend\Form\Form;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\ORM\EntityManager;//\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class ProfileForm extends Form //implements ObjectManagerAwareInterface
{
    protected $objectManager;
    public function __construct($name = null,$sm)
    {
        // we want to ignore the name passed
        //$this->setObjectManager($objectManager);
        parent::__construct('profile');
        
        $hydrator = new DoctrineHydrator($sm->get('doctrine.entitymanager.orm_default'), '\Application\Entity\UserProfiles');
        $this->setHydrator($hydrator);
        
        $this->add(array(
            'name' => 'displayname', // 'usr_name',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Stage Name / Nickname'
            )
        ));
        
        $this->add(array(
            'name' => 'first_name', // 'usr_name',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'First Name'
            )
        ));
        $this->add(array(
            'name' => 'last_name', // 'usr_name',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Last Name'
            )
        ));
        $this->add(array(
            'name' => 'birthdate', // 'usr_name',
            'type' => 'Date',
            'options' => array(
                'label' => 'Birthdate'
            ),
            'attributes' => array(
                'id' => 'birthdate',
                'class' => 'form-control',
                'min' => '1950-01-01',
                'max' => date('Y-m-d', mktime(0, 0, 0, date("m"), date("d"), date("Y") - 12)),
                'step' => '1' // days; default step interval is 1 day
                        )
        ));
        $this->add(array(
            'type' => 'Select',
            'name' => 'gender',
            'attributes' => array(
                'class' => 'form-control chosen-select-no-search',
                'id' => 'select-gender',
            ),
            'options' => array(
                'label' => 'Gender',
                'empty_option' => 'Please choose your gender',
                'value_options' => array(
                    'F' => 'Female',
                    'M' => 'Male',
                    'O' => 'Other'
                )
            )
        ));
        $this->add(array(
        		'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
        		'name'    => 'country',
                'attributes' => array(
                		'class' => 'form-control chosen-select-deselect',
                        'id' => 'select-country',
                ),
        		'options' => array(
        				'label'          => 'Choose your country',
        				'object_manager' => $sm->get('doctrine.entitymanager.orm_default'),
        				'target_class'   => 'Application\Entity\Countries',
        				'property'       => 'country_name',
        				'empty_option'   => '--- please choose ---'
        		),
        ));
        $this->add(array(
            'name' => 'biography', // 'usr_name',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Bio'
            )
        ));
        $this->add(array(
        		'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
        		'name'    => 'genres',
                'attributes' => array(
                    'multiple' => 'multiple',
                    'class' => 'chosen-select-multiple form-control',
                    'id' => 'select-genres',
                ),
        		'options' => array(
        				'label'          => 'Choose favorite genres',
        				'object_manager' => $sm->get('doctrine.entitymanager.orm_default'),
        				'target_class'   => 'Application\Entity\Genres',
        				'property'       => 'name'
        		),
        ));
        $this->add(array(
            'name' => 'facebook_link', // 'usr_name',
            'attributes' => array(
                'type' => 'Url',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Facebook Profile'
            )
        ));
        $this->add(array(
            'name' => 'twitter_link', // 'usr_name',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Twitter username'
            )
        ));
        $this->add(array(
            'name' => 'webpage', // 'usr_name',
            'attributes' => array(
                'type' => 'Url',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Webpage'
            )
        ));
        
        $this->add(array(
        		'name' => 'gravatar_email', // 'usr_name',
        		'attributes' => array(
        				'type' => 'Email',
                'class' => 'form-control',
        		),
        		'options' => array(
        				'label' => 'Gravatar Email'
        		)
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Save Profile',
                'id' => 'submitbutton'
            )
        ));
    }
    
    public function setObjectManager(EntityManager $objectManager)
    {
    	$this->objectManager = $objectManager;
    
    	return $this;
    }
    
    public function getObjectManager()
    {
    	return $this->objectManager;
    }
}