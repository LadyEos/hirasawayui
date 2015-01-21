<?php
namespace Workspace\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterProviderInterface;

class SongFilter extends InputFilter
{

    public function __construct($sm)
    {
        // self::__construct(); // parnt::__construct(); - trows and error
        $this->add(array(
            'name' => 'name',
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
                        'min' => 1,
                        'max' => 128
                    )
                )
            )
        ));
        
        $this->add(array(
            'name' => 'description',
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
                        'max' => 300
                    )
                )
            )
        ));
        
        $this->add(array(
            'name' => 'notes',
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
                        'max' => 300
                    )
                )
            )
        ));
        
        $this->add(array(
            'name' => 'completed',
            'required' => false
        ));
    }
}