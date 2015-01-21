<?php
namespace Messages\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterProviderInterface;

class ComposeFilter extends InputFilter
{

    public function __construct()
    {
        $this->add(array(
            'name' => 'subject',
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
                        'max' => 100
                    )
                )
            )
        ));
        
        $this->add(array(
        		'name' => 'body',
        		'required' => true,
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
            						'max' => 500
            				)
            		)
            )
        ));
        
        $this->add(array(
        		'name' => 'users',
        		'required' => true,
        ));
    }
}