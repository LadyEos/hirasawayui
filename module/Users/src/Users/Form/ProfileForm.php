<?php
namespace Users\Form;

use Zend\Form\Form;

class ProfileForm extends Form
{

    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('profile');
        
        $this->add(array(
            'name' => 'displayname', // 'usr_name',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Display Name'
            )
        ));
        $this->add(array(
            'name' => 'first_name', // 'usr_name',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'First Name'
            )
        ));
        $this->add(array(
            'name' => 'last_name', // 'usr_name',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Last Name'
            )
        ));
        $this->add(array(
            'name' => 'birthdate', // 'usr_name',
            'type' => 'Date',
            'attributes' => array(
                'id' => 'birthdate'
            ),
            'options' => array(
                'label' => 'Birthdate'
            ),
            'attributes' => array(
                'min' => '1950-01-01',
                'max' => date('Y-m-d', mktime(0, 0, 0, date("m"), date("d"), date("Y") - 12)),
                'step' => '1' // days; default step interval is 1 day
                        )
        ));
        $this->add(array(
            'type' => 'Select',
            'name' => 'gender',
            'options' => array(
                'label' => 'Gender',
                'empty_option' => 'Please choose your gender',
                'value_options' => array(
                    'F' => 'Female',
                    'M' => 'Male',
                    'O' => 'Other'
                )
            )
        ));
        $this->add(array(
            'name' => 'biography', // 'usr_name',
            'attributes' => array(
                'type' => 'textarea'
            ),
            'options' => array(
                'label' => 'Bio'
            )
        ));
        $this->add(array(
            'name' => 'facebook_link', // 'usr_name',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Facebook Profile'
            )
        ));
        $this->add(array(
            'name' => 'twitter_link', // 'usr_name',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Twitter username'
            )
        ));
        $this->add(array(
            'name' => 'webpage', // 'usr_name',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Webpage'
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Save Profile',
                'id' => 'submitbutton'
            )
        ));
    }
}