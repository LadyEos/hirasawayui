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

    public function __construct($name = null, $sm, $lyrics = false, $audio = false)
    {
        parent::__construct($name);
        
        $hydrator = new DoctrineHydrator($sm->get('doctrine.entitymanager.orm_default'), '\Application\Entity\SongsVersionHistory');
        $this->setHydrator($hydrator);
        
        $this->add(array(
            'name' => 'version',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Version Number'
            )
        ));
        
        if ($audio) {
            $file = new Element\File('file');
            $file->setLabel('File Input')->setAttributes(array(
                'id' => 'file'
            ));
            $this->add($file);
        }
        
        if ($lyrics) {
            $this->add(array(
                'name' => 'lyrics',
                'attributes' => array(
                    'type' => 'textarea'
                ),
                'options' => array(
                    'label' => 'Lyrics'
                )
            ));
        }
        $this->add(array(
            'name' => 'comments',
            'attributes' => array(
                'type' => 'textarea'
            ),
            'options' => array(
                'label' => 'Version Comments'
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
    }
}