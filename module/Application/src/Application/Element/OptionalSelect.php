<?php
namespace Application\Element;

use DoctrineModule\Form\Element\ObjectSelect;

class OptionalSelect extends ObjectSelect {

	public function getInputSpecification() {
		$inputSpecification = parent::getInputSpecification();
		$inputSpecification['required'] = isset($this->attributes['required']) && $this->attributes['required'];
		return $inputSpecification;
	}

}