<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class SongsVersionHistory {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/** @ORM\Column(type="string", length=300, nullable=TRUE) */
	protected $url;
	
	/** @ORM\Column(type="string", length=2000,nullable=TRUE) */
	protected $lyrics;

	/** @ORM\Column(type="datetime") */
	protected $created;

	/** @ORM\Column(type="string", length=30, nullable=TRUE) */
	protected $version;
	
	/** @ORM\Column(type="string", length=300, nullable=TRUE) */
	protected $comments;
	
	/** @ORM\Column(type="string", length=30, nullable=TRUE) */
	protected $sampletype;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Songs", inversedBy="versions")
	 * @ORM\JoinColumn(name="song_id", referencedColumnName="id")
	 **/
	protected $songs;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Users", inversedBy="versions")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 **/
	protected $users;
	
	public function __construct(){
	    $this->created = new \DateTime();
	}
	
	// getters/setters
	
	public function getId(){
		return $this->id;
	}
	
	public function getUrl(){
		return $this->url;
	}
	
	public function setUrl($url){
		$this->url = $url;
	}
	
	public function getLyrics(){
		return $this->lyrics;
	}
	
	public function setLyrics($lyrics){
		$this->lyrics = $lyrics;
	}
	
	public function getCreated(){
		return $this->created;
	}
	
	public function getVersion(){
		return $this->version;
	}
	
	public function setVersion($version){
		$this->version = $version;
	}
	
	public function getComments(){
		return $this->comments;
	}
	
	public function setComments($comments){
		$this->comments = $comments;
	}
	
	public function getSampleType(){
		return $this->sampletype;
	}
	
	public function setSampleType($sampletype){
		$this->sampletype = $sampletype;
	}
	
	public function getUsers(){
		return $this->users;
	}
	
	public function setUsers($users){
		$this->users = $users;
	}
	
	public function getSongs(){
		return $this->songs;
	}
	
	public function setSongs($songs){
		$this->songs = $songs;
	}
	
	/**
	 * Convert the object to an array.
	 *
	 * @return array
	 */
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
	
	/**
	 * Populate from an array.
	 *
	 * @param array $data
	 */
	public function populate($data = array())
	{
	    if(array_key_exists('id', $data))
	       $this->id = $data['id'];
	    
	    if(array_key_exists('url', $data))
		  $this->url = $data['url'];
	    
	    if(array_key_exists('lyrics', $data))
		  $this->lyrics = $data['lyrics'];
		 
		if(array_key_exists('created', $data))
			$this->created = $data['created'];
		 
		if(array_key_exists('version', $data))
			$this->version = $data['version'];
		 
		if(array_key_exists('comments', $data))
			$this->comments = $data['comments'];
		
		if(array_key_exists('sampletype', $data))
			$this->sampletype = $data['sampletype'];
		 
		if(array_key_exists('songs', $data))
			$this->songs= $data['songs'];
		 
		if(array_key_exists('users', $data))
			$this->users= $data['users'];

	}
	
	public function get(){
		return $this;
	}
}