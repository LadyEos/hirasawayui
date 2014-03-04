<?php
namespace Workspace\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\ORM\EntityManager; // \ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class LyricsForm extends Form // implements ObjectManagerAwareInterface
{

    public function __construct($name = null,$sm)
    {
        // we want to ignore the name passed
        // $this->setObjectManager($objectManager);
        parent::__construct($name);
        
        $hydrator = new DoctrineHydrator($sm->get('doctrine.entitymanager.orm_default'), '\Application\Entity\SongsVersionHistory');
        $this->setHydrator($hydrator);
        
        $this->add(array(
            'name' => 'version', // 'usr_name',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Version Number'
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
        		'name' => 'comments', // 'usr_name',
        		'attributes' => array(
        				'type' => 'textarea'
        		),
        		'options' => array(
        				'label' => 'Version Comments'
        		)
        ));
        
        /* if (! $sample) {
        	$radio = new Element\Radio('audio');
        	$radio->setLabel('Add audio/voice track?');
        	$radio->setValueOptions(array(
        			'0' => 'don\'t add any audio',
        			'1' => 'add to this version',
        			'2' => 'add in another version'
        	));
        	$radio->setValue('0');
        
        	$this->add($radio);
        } */
        
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