<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class SongCategories {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/** @ORM\Column(type="string", length=30) */
	protected $category_name;


	/** @ORM\Column(type="string", length=300) */
	protected $description;
	
	/**
	 * @ORM\OneToMany(targetEntity="Songs", mappedBy="categories")
	 **/
	protected $songs;
	
	public function __construct() {
		$this->songs = new \ArrayCollection();
	}

	// getters/setters

	public function getId(){
		return $this->id;
	}

	public function getCategory_name(){
		return $this->category_name;
	}

	public function setCategory_name($category_name){
		$this->category_name = $category_name;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
	}
	
	public function getSongs(){
		return $this->songs;
	}
	
	public function setSongs($songs){
		$this->songs = $songs;
	}

}