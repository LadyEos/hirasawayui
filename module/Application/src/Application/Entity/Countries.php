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

	/** @ORM\Column(type="string", length=100) */
	protected $country_code;
	
	/** @ORM\Column(type="string", length=100) */
	protected $country_name;
	
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

	public function getCountryName(){
		return $this->country_name;
	}

	public function setCountryName($name){
		$this->country_name = $name;
	}
	
	public function getCountryCode(){
		return $this->country_code;
	}
	
	public function setCountryCode($code){
		$this->country_code = $code;
	}
	
	public function getProfiles(){
		return $this->profiles;
	}
	
	public function setProfiles($profiles){
		$this->profiles = $profiles;
	}
	
}