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

	/** @ORM\Column(type="string", length=300) */
	protected $url;

	/** @ORM\Column(type="datetime") */
	protected $created;

	/** @ORM\Column(type="string", length=30) */
	protected $version;
	
	/** @ORM\Column(type="string", length=300) */
	protected $comments;
	
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
}