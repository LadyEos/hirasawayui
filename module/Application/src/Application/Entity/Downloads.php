<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Downloads {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
    
	/** @ORM\Column(type="string", length=100) */
	protected $dkey;
	
	/** @ORM\Column(type="string", length=300) */
	protected $file;
	
	/** @ORM\Column(type="integer", length=3) */
	protected $downloads;
	
	/** @ORM\Column(type="datetime") */
	protected $expires;
	
	/** @ORM\Column(type="datetime") */
	protected $created;
	
	/**
     * @ORM\ManyToOne(targetEntity="Songs", inversedBy="downloads")
     * @ORM\JoinColumn(name="song_id", referencedColumnName="id",nullable=true)
     **/
	protected $song;

	/**
	 * @ORM\ManyToOne(targetEntity="Albums", inversedBy="downloads")
	 * @ORM\JoinColumn(name="album_id", referencedColumnName="id",nullable=true)
	 **/
	protected $album;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Users", inversedBy="downloads")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 **/
	protected $user;
	

	public function __construct(){
		$this->created = new \DateTime();
		$expires = new \DateTime();
		$this->expires = $expires->add(new \DateInterval('P1D'));
		$this->downloads =0;
	}

	// getters/setters

	public function getId(){
		return $this->id;
	}

	public function getCreated(){
		return $this->created;
	}
	
	public function getDownloads(){
		return $this->downloads;
	}
	
	public function setDownloads($download){
		$this->downloads = $download;
	}
	
	public function addDownloads(){
		$this->downloads = $this->downloads+1;
	}
	
	public function getExpires(){
		return $this->expires;
	}
	
	public function setExpires($expires){
		return $this->expires = $expires;
	}

	public function getFile(){
		return $this->file;
	}

	public function setFile($file){
		$this->file = $file;
	}

	public function getKey(){
		return $this->dkey;
	}
	
	public function setKey($key){
		$this->dkey = $key;
	}
	
	public function getSong(){
		return $this->song;
	}
	
	public function setSong(Songs $song){
		$this->song = $song;
	}
	
	public function getAlbum(){
		return $this->album;
	}
	
	public function setAlbum(Albums $album){
		$this->album = $album;
	}
	
	public function getUser(){
		return $this->user;
	}
	
	public function setUser(Users $user){
		$this->user = $user;
	}
	
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
	
	public function populate($data = array())
	{
		if(array_key_exists('id', $data))
			$this->id = $data['id'];
	
		if(array_key_exists('file', $data))
			$this->file = $data['file'];
			
		if(array_key_exists('key', $data))
			$this->dkey = $data['key'];
			
		if(array_key_exists('song', $data))
			$this->song = $data['song'];
			
		if(array_key_exists('user', $data))
			$this->user = $data['user'];
			
	}
	
	public function get(){
		return $this;
	}
}