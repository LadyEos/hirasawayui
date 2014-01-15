<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Songs {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/** @ORM\Column(type="string", length=30) */
	protected $name;
	
	/** @ORM\Column(type="boolean", length=1) */
	protected $completed;

	/** @ORM\Column(type="string", length=500) */
	protected $notes;
	
	/** @ORM\Column(type="string", length=300) */
	protected $description;

	/** @ORM\Column(type="datetime") */
	protected $created;
	
	/**
	 * @ORM\OneToMany(targetEntity="SoldSongs", mappedBy="songs")
	 **/
	protected $sold;
	
	/**
	 * @ORM\OneToMany(targetEntity="SongsVersionHistory", mappedBy="songs")
	 **/
	protected $versions;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Genres", inversedBy="songs")
	 * @ORM\JoinColumn(name="genre_id", referencedColumnName="id")
	 **/
	protected $genres;
	
	/**
	 * @ORM\ManyToOne(targetEntity="SongCategories", inversedBy="songs")
	 * @ORM\JoinColumn(name="song_category_id", referencedColumnName="id")
	 **/
	protected $categories;
	
	/**
	 * @ORM\OneToMany(targetEntity="Likes", mappedBy="songs")
	 **/
	protected $likes;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Albums", inversedBy="songs")
	 * @ORM\JoinColumn(name="song_id", referencedColumnName="id")
	 **/
	protected $albums;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Users", mappedBy="songs")
	 */
	protected $users;


	public function __construct(){
		$this->created = new \DateTime();
		$this->activated = FALSE;
		$this->sold = new \Doctrine\Common\Collections\ArrayCollection();
		$this->versions = new \Doctrine\Common\Collections\ArrayCollection();
		$this->likes = new \Doctrine\Common\Collections\ArrayCollection();
		$this->users = new \Doctrine\Common\Collections\ArrayCollection();
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

	public function getCompleted(){
		return $this->completed;
	}

	public function setCompleted($completed){
		$this->completed = $completed;
	}

	public function getNotes(){
		return $this->notes;
	}

	public function setNotes($notes){
		$this->notes = $notes;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getCreated(){
		return $this->created;
	}
	
	public function getGenres(){
		return $this->genres;
	}
	
	public function setGenres($genres){
		$this->genres = $genres;
	}
	
	public function getCategories(){
		return $this->categories;
	}
	
	public function setCategories($categories){
		$this->categories = $categories;
	}
	
	public function getAlbums(){
		return $this->albums;
	}
	
	public function setAlbums($albums){
		$this->albums = $albums;
	}
	
	public function getSold(){
		return $this->sold;
	}
	
	public function setSold($sold){
		$this->sold = $sold;
	}
	
	public function getVersions(){
		return $this->versions;
	}
	
	public function setVersions($versions){
		$this->versions = $versions;
	}
	
	public function getLikes(){
		return $this->likes;
	}
	
	public function setLikes($likes){
		$this->likes = $likes;
	}
}