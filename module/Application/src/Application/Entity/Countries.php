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
	 * @ORM\OneToMany(targetEntity="UserProfiles", mappedBy="country")
	 **/
	protected $profiles;
	
	/**
	 * @ORM\OneToMany(targetEntity="BankAccounts", mappedBy="country")
	 **/
	protected $banks;
	
	

	public function __construct() {
	    $this->profiles = new \ArrayCollection();
	    $this->banks = new \ArrayCollection();
	}

	// getters/setters

	public function getId(){
		return $this->id;
	}

	public function getCountry_name(){
		return $this->country_name;
	}

	public function setCountry_name($name){
		$this->country_name = $name;
	}
	
	public function getCountry_code(){
		return $this->country_code;
	}
	
	public function setCountry_code($code){
		$this->country_code = $code;
	}
	
	public function getProfiles(){
		return $this->profiles;
	}
	
	public function setProfiles($profiles){
		$this->profiles = $profiles;
	}
	
	public function getBanks(){
		return $this->banks;
	}
	
	public function setBanks($banks){
		$this->banks = $banks;
	}
	
}