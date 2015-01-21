<?php
namespace Admin\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\I18n\Validator\Float;

class RoleFilter extends InputFilter
{

    public function __construct()
    {
        // self::__construct(); // parnt::__construct(); - trows and error
        $this->add(array(
            'name' => 'height',
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
                    'name' => 'Zend\Validator\Between',
                    'options' => array(
                        'min'=>1,
                        'max'=>10,
                        'inclusive' => false
                    )
                ),
                array(
                		'name' => 'StringLength',
                		'options' => array(
                				'encoding' => 'UTF-8',
                				'min' => 1,
                				'max' => 1
                		)
                )
            )
        ));
        
        $this->add(array(
        		'name' => 'role',
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
        		'name' => 'rolekey',
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
        								'max' => 3
        						)
        				)
        		)
        ));
        
    }
}