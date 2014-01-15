<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Albums {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/** @ORM\Column(type="string", length=30) */
	protected $name;

	/** @ORM\Column(type="boolean") */
	protected $is_complete;

	/**
	 * @ORM\OneToMany(targetEntity="Songs", mappedBy="albums")
	 **/
	protected $songs;

	public function __construct() {
		$this->songs = new ArrayCollection();
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

	public function getIs_complete(){
		return $this->is_complete;
	}

	public function setIs_complete($is_complete){
		$this->is_complete = $is_complete;
	}
	
	public function getSongs(){
		return $this->songs;
	}
	
	public function setSongs($songs){
		$this->songs = $songs;
	}
}