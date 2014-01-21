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
		$this->add(array(
            'name' => 'image-file',
            'attributes' => array(
                'type' => 'File',
                'id' => 'image-file'
            ),
            'options' => array(
                'label' => 'Avatar Image Upload',
            )
        ));
		$this->add(array(
				'name' => 'submit',
				'attributes' => array(
						'type' => 'submit',
						'value' => 'Upload File',
						'id' => 'submitbutton'
				)
		));
		
		//$this->addInputFilter();
	}
	
	public function addInputFilter()
	{
		$inputFilter = new InputFilter\InputFilter();
	
		// File Input
		$fileInput = new InputFilter\FileInput('image-file');
		$fileInput->setRequired(true);
		$fileInput->getFilterChain()->attachByName(
				'filerenameupload',
				array(
						'target'    => './data/tmpuploads/avatar.png',
						'randomize' => true,
				        'use_upload_extension' => true,
				)
		);
		$inputFilter->add($fileInput);
	
		$this->setInputFilter($inputFilter);
	}

}