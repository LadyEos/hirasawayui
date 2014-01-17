<?php
namespace Users\Form;

use Application\Entity\Users;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class UsersFieldset extends Fieldset
{

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('user');
        
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new Users());
        
        $this->add(array(
            'name' => 'displayname', // 'usr_name',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Display Name'
            )
        ));
    }
}