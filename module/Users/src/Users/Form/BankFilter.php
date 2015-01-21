<?php
namespace Users\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterProviderInterface;

class BankFilter extends InputFilter
{

    public function __construct($sm)
    {
        // self::__construct(); // parnt::__construct(); - trows and error
        $this->add(array(
            'name' => 'paypal',
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
                        'min' => 1,
                        'max' => 128
                    )
                )
            )
        ));
        
        $this->add(array(
            'name' => 'bankname',
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
                        'min' => 1,
                        'max' => 50
                    )
                )
            )
        ));
        
        $this->add(array(
            'name' => 'cardholder',
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
                        'min' => 1,
                        'max' => 100
                    )
                )
            )
        ));
        
        $this->add(array(
            'name' => 'account',
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
                    'name' => 'Alnum',
                    'options' => array(
                        'allowWhiteSpace' => true
                    )
                ),
                array(
                    'name' => 'CreditCard'	
                )
            )
        ));
    }
}