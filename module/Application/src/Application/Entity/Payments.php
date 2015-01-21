<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Payments {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Users", inversedBy="payments")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 **/
	protected $users;

	/** @ORM\Column(type="datetime") */
	protected $date;

	/** @ORM\Column(type="decimal", precision=6, scale=3 ) */
	protected $amount;

	/** @ORM\Column(type="string", length=300) */
	protected $concept;
	
	/** @ORM\Column(type="boolean") */
	protected $paid;
	
	/*/**
	 * @ORM\OneToMany(targetEntity="SoldSongs", mappedBy="orders")
	 **/
	//protected $songs;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Songs", inversedBy="sold")
	 * @ORM\JoinTable(name="SoldSongs")
	 **/
	protected $songs;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Albums", inversedBy="sold")
	 * @ORM\JoinTable(name="SoldAlbums")
	 **/
	protected $albums;

	public function __construct(){
	    $this->paid = false;
		$this->date = new \DateTime();
		$this->songs = new \Doctrine\Common\Collections\ArrayCollection();
		$this->albums = new \Doctrine\Common\Collections\ArrayCollection();
	}

	// getters/setters

	public function getId(){
		return $this->id;
	}

	public function getDate(){
		return $this->date;
	}

	public function getAmount(){
		return $this->amount;
	}

	public function setAmount($amount){
		$this->amount = $amount;
	}

	public function getConcept(){
		return $this->concept;
	}

	public function setConcept($concept){
		$this->concept = $concept;
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
	
	public function getAlbums(){
		return $this->albums;
	}
	
	public function setAlbums($albums){
		$this->albums = $albums;
	}
	
	/**
	 * Populate from an array.
	 *
	 * @param array $data
	 */
	public function populate($data = array())
	{
		if (array_key_exists('amount', $data))
			$this->amount = $data['amount'];
	
		if (array_key_exists('concept', $data))
			$this->concept = $data['concept'];
	
		if (array_key_exists('date', $data))
			$this->date = $data['date'];
	
		if (array_key_exists('songs', $data))
			$this->songs = $data['songs'];
	
		if (array_key_exists('users', $data))
			$this->users = $data['users'];

		return $data;
	}
	
	public function getArrayCopy(){
		$data = get_object_vars($this);
		return $data;
	}
	
	/* Collection Songs */
	
	public function hasSongs(Songs $song) {
		$songs = array();
		foreach ($this->getSongs() as $arrMember) {
			$songs[] = $arrMember->getId();
		}
		if (in_array($song->getId(), $songs))    //check if the supplied language is to be removed or not
			return true;
		else
			return false;
	}
	
	public function removeSong (Songs $song) {
		$this->songs->removeElement($song);
		$song->unsetBought($this);
	}
	
	public function addSongs (Songs $song) {
		$song->setBought($this);
		$this->songs[] = $song;
	}
	
	public function hasAlbums(Albums $album) {
		$albums = array();
		foreach ($this->getAlbums() as $arrMember) {
			$albums[] = $arrMember->getId();
		}
		if (in_array($album->getId(), $albums))    //check if the supplied language is to be removed or not
			return true;
		else
			return false;
	}
	
	public function removeAlbum (Albums $album) {
		$this->albums->removeElement($album);
		$album->unsetBought($this);
	}
	
	public function addAlbum (Albums $album) {
		$album->setBought($this);
		$this->albums[] = $album;
	}
}