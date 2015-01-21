<?php
namespace Admin\Form;

use Zend\Form\Form;

class PriceForm extends Form //implements ObjectManagerAwareInterface
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        //$this->setObjectManager($objectManager);
        parent::__construct('Role');
        
       
        $this->add(array(
            'name' => 'amount', // 'usr_name',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Number'
            ),
            'options' => array(
                'label' => 'Amount'
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