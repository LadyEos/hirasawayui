<?php
namespace Search\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterProviderInterface;

class UserFilter extends InputFilter
{

    public function __construct()
    {
        $this->add(array(
            'name' => 'keyword',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 3,
                        'max' => 30
                    )
                )
            )
        ));
        
        $this->add(array(
        		'name' => 'genre',
        		'required' => false,
        ));
    }
}