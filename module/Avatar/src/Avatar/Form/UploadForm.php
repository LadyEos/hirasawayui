<?php
namespace Avatar\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter;

class UploadForm extends Form
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->setInputFilter($this->createInputFilter());
    }

    public function addElements()
    {
        // File Input
        $file = new Element\File('file');
        $file->setLabel('File Input')->setAttributes(array(
            'id' => 'file'
        ));
        $this->add($file);
        
        $this->add(array(
        		'name' => 'submit',
        		'attributes' => array(
        				'type' => 'submit',
        				'value' => 'Upload File',
        				'id' => 'submitbutton'
        		)
        ));
    }

    public function createInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();
        
        // File Input
        $file = new InputFilter\FileInput('file');
        $file->setRequired(true);
        $file->getFilterChain()->attachByName('filerenameupload', array(
            'target' => './data/tmpuploads/',
            'overwrite' => false,
            'use_upload_name' => false,
            'randomize' => true,
            'use_upload_extension' => true,
        ));
        $inputFilter->add($file);
        
        return $inputFilter;
    }
}