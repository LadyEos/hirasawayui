<?php
namespace Workspace\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterProviderInterface;

class UploadFilter extends InputFilter
{

    public function __construct($sm)
    {
        // self::__construct(); // parnt::__construct(); - trows and error
        $this->add(array(
            'name' => 'version',
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
                        'max' => 6
                    )
                ),
                // /*
                array(
                    'name' => 'Regex',
                    'options' => array(
                        'pattern' => '/(\d+)((\.\d+)+)?/',
                        //'pattern' => '/\d+(\.\d+)+$/',
                        'messages' => array(
                            'regexInvalid' => 'Regex is invalid, Booo!',
                            'regexNotMatch' => 'Input doesn\'t match, bleeeeh!'
                        // 'regexError'=> 'Internal error, i\'m like wtf!'
                                                )
                    )
                )
            // */
                        )
        ));
        
        $this->add(array(
            'name' => 'comments',
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
            'name' => 'file',
            'required' => false,
            'validators' => array(
                array(
                    'name' => '\File\Extension',
                    'options' => array(
                        'extension' => array(
                            'mp3'
                        )
                    )
                )
            )
        ));
        
        $this->add(array(
        		'name' => 'lyrics',
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
        								'max' => 2000
        						)
        				)
        		)
        ));
    }
}