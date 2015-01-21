<?php
namespace Users\Form;

use Zend\Form\Form;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\ORM\EntityManager; // \ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Application\Element\OptionalSelect;

class BankForm extends Form // implements ObjectManagerAwareInterface
{

    public function __construct($name = null, $sm)
    {
        // we want to ignore the name passed
        // $this->setObjectManager($objectManager);
        parent::__construct($name);
        
        $hydrator = new DoctrineHydrator($sm->get('doctrine.entitymanager.orm_default'), '\Application\Entity\BankAccounts');
        $this->setHydrator($hydrator);
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'payment',
            'options' => array(
                'label' => 'What payment method do you prefer?',
                'value_options' => array(
                    '0' => 'Paypal',
                    '1' => 'Bank Deposit'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'paypal', // 'usr_name',
            'attributes' => array(
                'type' => 'Email',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Paypal email account'
            )
        ));
        
        $this->add(array(
            'name' => 'bankname', // 'usr_name',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Bank Name'
            )
        ));
        
        $this->add(array(
            'name' => 'cardholder', // 'usr_name',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Name'
            )
        ));
        
        $this->add(array(
            'name' => 'account', // 'usr_name',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Account number'
            )
        ));
        
        $this->add(array(
            //'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'type' => 'Application\Element\OptionalSelect',
            'name' => 'country',
            'attributes' => array(
                'class' => 'form-control chosen-select-deselect',
                'id' => 'select-country'
            ),
            'options' => array(
                'label' => 'Choose your country',
                'object_manager' => $sm->get('doctrine.entitymanager.orm_default'),
                'target_class' => 'Application\Entity\Countries',
                'property' => 'country_name',
                'empty_option' => '--- please choose ---',
                'required' => false,
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
}