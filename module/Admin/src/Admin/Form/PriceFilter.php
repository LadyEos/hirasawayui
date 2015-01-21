<?php
namespace Admin\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\I18n\Validator\Float;

class PriceFilter extends InputFilter
{

    public function __construct()
    {
        // self::__construct(); // parnt::__construct(); - trows and error
        $this->add(array(
            'name' => 'amount',
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
                    'name' => 'Zend\I18n\Validator\Float',
                    'options' => array(
                        'locale' => 'en_US',
                        'messages'=> array('notFloat'=>'please input a valid amount in this format: 0.00')
                    )
                ),
                array(
                		'name' => 'StringLength',
                		'options' => array(
                				'encoding' => 'UTF-8',
                				'min' => 1,
                				'max' => 6
                		)
                )
            )
        ));
    }
}