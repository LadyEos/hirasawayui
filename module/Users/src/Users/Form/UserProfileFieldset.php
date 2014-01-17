<?php
namespace Application\Form;

use Application\Entity\UserProfiles;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineORMModuleTest\Assets\GraphEntity\User;

class UserProfileFieldset extends Fieldset
{
	public function __construct(ObjectManager $objectManager)
	{
		parent::__construct('userprofile');

		$this->setHydrator(new DoctrineHydrator($objectManager))
		->setObject(new UserProfiles());

		$this->add(array(
            'name' => 'first_name', // 'usr_name',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'First Name'
            )
        ));
        $this->add(array(
            'name' => 'last_name', // 'usr_name',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Last Name'
            )
        ));
        $this->add(array(
            'name' => 'birthdate', // 'usr_name',
            'type' => 'Date',
            'attributes' => array(
            	'id' => 'birthdate'
            ),
            'options' => array(
                'label' => 'Birthdate'
            ),
            'attributes' => array(
                'min' => '1950-01-01',
                'max' => date('Y-m-d',mktime(0, 0, 0, date("m"), date("d"),   date("Y")-12)),
                'step' => '1' // days; default step interval is 1 day
             )
        ));
        $this->add(array(
            'name' => 'biography', // 'usr_name',
            'attributes' => array(
                'type' => 'textarea'
            ),
            'options' => array(
                'label' => 'Bio'
            )
        ));
        $this->add(array(
            'name' => 'facebook_link', // 'usr_name',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Facebook Profile'
            )
        ));
        $this->add(array(
        		'name' => 'twitter_link', // 'usr_name',
        		'attributes' => array(
        				'type' => 'text'
        		),
        		'options' => array(
        				'label' => 'Twitter username'
        		)
        ));
        $this->add(array(
        		'name' => 'webpage', // 'usr_name',
        		'attributes' => array(
        				'type' => 'text'
        		),
        		'options' => array(
        				'label' => 'Webpage'
        		)
        ));
        $this->add(array(
        	'name' => 'id',
            'type' => 'hidden'
        ));

		$userFieldset = new UserFieldset($objectManager);
		$this->add(array(
				'type'    => 'Collection',
				'name'    => 'user',
				'options' => array(
						'count'           => 1,
						'target_element' => $userFieldset
				)
		));
	}
}