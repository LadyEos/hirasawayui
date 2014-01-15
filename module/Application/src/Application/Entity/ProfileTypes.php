<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
/** @ORM\Entity */
class ProfileTypes {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	
	/** @ORM\Column(type="string", length=30) */
	protected $profile_name;

	/** @ORM\Column(type="string", length=3) */
	protected $profile_key;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Application\Entity\Users", mappedBy="profile_types")
	 */
	protected $users;
	
	public function __construct() {
		$this->users = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	// getters/setters

	public function getId(){
		return $this->id;
	}

    function getProfile_name(){
		return $this->profile_name;
	}

	public function setProfile_name($profile_name){
		$this->profile_name = $profile_name;
	}

	public function getProfile_key(){
		return $this->profile_key;
	}

	public function setProfile_key($profile_key){
		$this->profile_key = $profile_key;
	}
}