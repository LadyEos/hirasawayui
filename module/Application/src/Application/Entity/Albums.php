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
	protected $completed;
	
	/** @ORM\Column(type="datetime") */
	protected $created;
	
	/** @ORM\Column(type="string", length=300) */
	protected $description;
	
	/** @ORM\Column(type="boolean", length=1) */
	protected $active;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Songs", mappedBy="albums")
	 **/
	protected $songs;
	
	/**
	 * @ORM\OneToMany(targetEntity="Prices", mappedBy="album")
	 * @ORM\OrderBy({"created" = "DESC"})
	 **/
	protected $price;
	
	/**
	 * @ORM\OneToMany(targetEntity="Downloads", mappedBy="album")
	 * @ORM\OrderBy({"created" = "DESC"})
	 **/
	protected $downloads;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Payments", mappedBy="albums")
	 **/
	protected $sold;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Users", mappedBy="albums")
	 */
	protected $users;

	public function __construct() {
	    $this->created = new \DateTime();
	    $this->active = TRUE;
		$this->songs = new \Doctrine\Common\Collections\ArrayCollection();
		$this->price = new \Doctrine\Common\Collections\ArrayCollection();
		$this->downloads = new \Doctrine\Common\Collections\ArrayCollection();
		$this->sold = new \Doctrine\Common\Collections\ArrayCollection();
		$this->users = new \Doctrine\Common\Collections\ArrayCollection();
	}

	// getters/setters

	public function getId(){
		return $this->id;
	}
	
	public function getCreated(){
		return $this->created;
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
	
	public function getPrice(){
		return $this->price;
	}
	
	public function setPrice($price){
		$this->price = $price;
	}
	
	public function getDownloads(){
		return $this->downloads;
	}
	
	public function setDownloads($downloads){
		$this->downloads = $downloads;
	}
	
	public function getSold(){
		return $this->sold;
	}
	
	public function setSold($sold){
		$this->sold = $sold;
	}
	
	public function getUsers(){
		return $this->users;
	}
	
	public function setUsers($users){
		$this->users = $users;
	}
	
	public function getActive(){
		return $this->active;
	}
	
	public function setActive($active){
		$this->active = $active;
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
		$this->name = $data['name'];
		$this->description = $data['description'];
		$this->completed = $data['completed'];
		if(array_key_exists('active', $data))
			$this->active = $data['active'];
			
	}
	
	public function get(){
		return $this;
	}
	
	
	public function hasSongs(Songs $song) {
		$songs = array();
		foreach ($this->getSongs() as $arrMember) {
			$songs[] = $arrMember->getName();
		}
		if (in_array($song->getName(), $songs))    //check if the supplied language is to be removed or not
			return true;
		else
			return false;
	}
	
	public function removeSong (Songs $song) {
		$this->songs->removeElement($song);
		$song->unsetAlbum($this);
	}
	
	public function addSong (Songs $song) {
	    if(!$this->hasSongs($song)){
    	    $song->setAlbum($this);
    		$this->songs[] = $song;
	    }
	}
	
	public function removeSongs($songs) {
		foreach($songs as $song){
			$this->removeSong($song);
		}
	}
	
	public function addSongs($songs) {
		foreach($songs as $song){
			if(!$this->hasSongs($song))
				$this->addSong($song);
		}
	}
	/* end Languages methods */
	
	public function countSongs(){
		return $this->songs->count();
	}
	
	
	public function hasUser(Users $user) {
		$users = array();
		foreach ($this->getUsers() as $arrMember) {
			$users[] = $arrMember->getId();
		}
		if (in_array($user->getId(), $users))    //check if the supplied language is to be removed or not
			return true;
		else
			return false;
	}
	
	public function unsetUser (Users $user) {
		$this->users->removeElement($user);
	}
	
	public function setUser (Users $user) {
		$this->users[] = $user;
	}
	
	public function hasPrice(Prices $price) {
		$prices = array();
		foreach ($this->getPrice() as $arrMember) {
			$prices[] = $arrMember->getAmount();
		}
		if (in_array($price->getAmount(), $prices))    //check if the supplied language is to be removed or not
			return true;
		else
			return false;
	}
	
	public function addPrice (Prices $price) {
		$price->setAlbum($this);
		$this->price[] = $price;
	}
	
	public function getLastPrice(){
		return $this->price[0];
	}
	
	public function wasBought (Payments $boughtAlbums) {
		$bought = array();
		foreach ($this->getSold() as $arrMember) {
			$bought[] = $arrMember->getId();
		}
		if (in_array($boughtAlbums->getId(), $bought))    //check if the supplied language is to be removed or not
			return true;
	}
	
	public function unsetBought (Payments $boughtAlbums) {
		$this->sold->removeElement($boughtAlbums);
	}
	
	public function setBought (Payments $boughtAlbums) {
		$this->sold[] = $boughtAlbums;
	}
	
}