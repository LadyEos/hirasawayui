<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Prices {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Songs",inversedBy="price")
	 * @ORM\JoinColumn(name="song_id", referencedColumnName="id", nullable=true)
	 **/
	protected $song;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Albums", inversedBy="price")
	 * @ORM\JoinColumn(name="album_id", referencedColumnName="id", nullable=true)
	 **/
	protected $album;

	/** @ORM\Column(type="decimal", precision=6, scale=3 ) */
	protected $amount;
	
	/** @ORM\Column(type="datetime") */
	protected $created;
	

	public function __construct(){
		$this->created = new \DateTime();
	}

	// getters/setters

	public function getId(){
		return $this->id;
	}
	
	public function getCreated(){
		return $this->created;
	}

	public function getAmount(){
		return $this->amount;
	}

	public function setAmount($amount){
		$this->amount = $amount;
	}

	public function getSong(){
		return $this->song;
	}
	
	public function setSong($song){
		$this->song = $song;
	}
	
	public function getAlbum(){
		return $this->album;
	}
	
	public function setAlbum($album){
		$this->album = $album;
	}
}