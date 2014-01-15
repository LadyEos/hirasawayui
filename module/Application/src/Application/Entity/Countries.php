<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Countries {
	/**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
	protected $id;

	/** @ORM\Column(type="string", length=255) */
	protected $name;
	
	/**
	 * @ORM\OneToMany(targetEntity="UserProfiles", mappedBy="countries")
	 **/
	protected $profiles;
	
	

	public function __construct() {
	    $this->profiles = new \ArrayCollection();
	}

	// getters/setters

	public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}
	
}