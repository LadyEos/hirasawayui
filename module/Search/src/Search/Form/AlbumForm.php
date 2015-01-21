<?php
namespace Search\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter;

class AlbumForm extends Form // implements ObjectManagerAwareInterface
{

    public function __construct($name = null)
    {
        // we want to ignore the name passed
        // $this->setObjectManager($objectManager);
        parent::__construct();
        
        $this->add(array(
            'name' => 'keyword', // 'usr_name',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Search'
            )
        ));
        
        $this->add(array(
            'name' => 'Search',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Search',
                'id' => 'submitbutton'
            )
        ));
    }

   
}