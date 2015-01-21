<?php
namespace Avatar\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterProviderInterface;

class UploadFilter extends InputFilter
{

    public function __construct($sm)
    {
        // self::__construct(); // parnt::__construct(); - trows and error
        $this->add(array(
            'name' => 'file',
            'required' => false,
            'validators' => array(
                array(
                    'name' => '\File\Extension',
                    'options' => array(
                        'extension' => array(
                            'jpg',
                            'png',
                            'gif'
                        )
                    )
                ),
                array(
                    'name' => '\File\ImageSize',
                    'options' => array(
                        'minWidth' => 100,
                        'minHeight' => 100,
                        'maxWidth' => 640,
                        'maxHeight' => 640
                    )
                )
            )
        ));
    }
}