<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Genres {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/** @ORM\Column(type="string", length=30) */
	protected $name;

	/** @ORM\Column(type="string", length=300) */
	protected $description;
	
	/**
     * @ORM\OneToOne(targetEntity="Genres")
     * @ORM\JoinColumn(name="is_subgenre_of", referencedColumnName="id")
     **/
    protected $subgenre;
    
    /**
     * @ORM\OneToMany(targetEntity="Songs", mappedBy="genre")
     **/
    protected $songs;
    
    /**
     * @ORM\ManyToMany(targetEntity="UserProfiles", mappedBy="genres")
     */
    protected $userprofiles;
    
    public function __construct() {
    	$this->songs = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->userprofiles = new \Doctrine\Common\Collections\ArrayCollection();
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

    function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
	}
	
	public function getSubgenres(){
		return $this->subgenre;
	}
	
	public function setSubgenres($subgenre){
		$this->subgenre = $subgenre;
	}
	
	public function getSongs(){
		return $this->songs;
	}
	
	public function setSongs($songs){
		$this->songs = $songs;
	}
	
	
	public function unsetUserProfile (UserProfiles $userprofile) {
		$this->userprofiles->removeElement($userprofile);
	}
	
	public function setUserProfile (UserProfiles $userprofile) {
		$this->userprofiles[] = $userprofile;
	}
}