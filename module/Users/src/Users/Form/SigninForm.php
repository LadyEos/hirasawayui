<?php
namespace Oldish\Form;

use Zend\Form\Form;

class SigninForm extends Form
{

    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('user');
        
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden'
        ));
        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
            'options' => array(
                'label' => 'Username',
                'size' => 32
            )
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'options' => array(
                'label' => 'Password',
                'size' => 32
            )
        ));
        
        $this->add(array(
            'name' => 'password_validation',
            'type' => 'Password',
            'options' => array(
                'label' => 'Retype Password',
                'size' => 32
            )
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'Text',
            'options' => array(
                'label' => 'E-mail'
            )
        ));
        /**
         * $form->add(array(
         * 'type' => 'Zend\Form\Element\Captcha',
         * 'name' => 'captcha',
         * 'options' => array(
         * 'label' => 'Please verify you are human',
         * 'captcha' => new Captcha\Dumb(),
         * ),
         * ));
         */
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton'
            )
        ));
    }
}