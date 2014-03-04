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

    public function __construct($name = null, $sm, $sample)
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
        
        $file = new Element\File('file');
        $file->setLabel('File Input')->setAttributes(array(
            'id' => 'file'
        ));
        $this->add($file);
        
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
            $radio = new Element\Radio('lyrics');
            $radio->setLabel('Add lyrics?');
            $radio->setValueOptions(array(
                '0' => 'don\'t add any lyrics',
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
                'value' => 'Upload',
                'id' => 'submitbutton'
            )
        ));
    }
}