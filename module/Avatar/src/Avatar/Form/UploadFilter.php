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
            'name' => 'image-file',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'Zend\Filter\File\RenameUpload',
                    'options' => array(
                        'target' => 'data/tmpuploads/avatar.png',
                        'randomize' => true,
                        'use_upload_extension' => true
                    )
                )
            )
        ));
    }
}