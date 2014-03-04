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
	
	/** @ORM\Column(type="boolean", length=1) */
	protected $sample;

	/** @ORM\Column(type="string", length=500) */
	protected $notes;
	
	/** @ORM\Column(type="string", length=300) */
	protected $description;
	
	/** @ORM\Column(type="string", length=30, nullable=TRUE) */
	protected $sampletype;
	
	/** @ORM\Column(type="boolean", length=1) */
	protected $active;

	/** @ORM\Column(type="datetime") */
	protected $created;
	
	/**
	 * @ORM\OneToMany(targetEntity="SoldSongs", mappedBy="songs")
	 **/
	protected $sold;
	
	/**
	 * @ORM\OneToMany(targetEntity="SongsVersionHistory", mappedBy="song", cascade={"persist", "remove"})
	 * @ORM\OrderBy({"created" = "DESC"})
	 **/
	protected $versions;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Genres", inversedBy="songs")
	 * @ORM\JoinColumn(name="genre_id", referencedColumnName="id")
	 **/
	protected $genre;
	
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
	 * @ORM\JoinColumn(name="album_id", referencedColumnName="id")
	 **/
	protected $albums;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Users", mappedBy="songs")
	 */
	protected $users;


	public function __construct(){
		$this->created = new \DateTime();
		$this->activate = TRUE;
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
	
 	public function getSample(){
		return $this->sample;
	}
	
	public function setSample($sample){
		$this->sample = $sample;
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
	
	public function getGenre(){
		return $this->genre;
	}
	
	public function setGenre($genre){
		$this->genre = $genre;
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
	
	public function getUsers(){
		return $this->users;
	}
	
	public function getSampleType(){
		return $this->sampletype;
	}
	
	public function setSampleType($sampletype){
		$this->sampletype = $sampletype;
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
		if(array_key_exists('id', $data))
			$this->id = $data['id'];
		
		if(array_key_exists('name', $data))
			$this->name = $data['name'];
		
		if(array_key_exists('completed', $data))
			$this->completed = $data['completed'];
		 
		if(array_key_exists('sample', $data))
			$this->sample = $data['sample'];
		 
		if(array_key_exists('notes', $data))
			$this->notes = $data['notes'];
		 
		if(array_key_exists('description', $data))
			$this->description = $data['description'];
		 
		if(array_key_exists('created', $data))
			$this->created= $data['created'];
		 
		if(array_key_exists('sold', $data))
			$this->sold= $data['sold'];
		 
		if(array_key_exists('versions', $data))
			$this->versions = $data['versions'];
		
		if(array_key_exists('genre', $data))
			$this->genre = $data['genre'];
		
		if(array_key_exists('categories', $data))
			$this->categories = $data['categories'];
		
		if(array_key_exists('likes', $data))
			$this->likes = $data['likes'];
		
		if(array_key_exists('albums', $data))
			$this->albums = $data['albums'];
		
		if(array_key_exists('users', $data))
			$this->users = $data['users'];
		
		if(array_key_exists('active', $data))
			$this->active = $data['active'];
		
		if(array_key_exists('sampletype', $data))
			$this->sampletype = $data['sampletype'];
	}
	
	public function get(){
		return $this;
	}
	
	/* Collection */
	
	public function hasVersion(SongsVersionHistory $song) {
		$versions = array();
		foreach ($this->getVersions() as $arrMember) {
			$versions[] = $arrMember->getId();
		}
		if (in_array($song->getId(), $versions))    //check if the supplied language is to be removed or not
			return true;
		else
			return false;
	}
	
	public function addVersion (SongsVersionHistory $version) {
		$version->setSong($this);
	    $this->versions[] = $version;
	}
	
	
	
	
	public function hasUser (Users $user) {
		$users = array();
		foreach ($this->getUsers() as $arrMember) {
			$users[] = $arrMember->getUsername();
		}
		if (in_array($user->getUsername(), $users))    //check if the supplied language is to be removed or not
			return true;
	}
	
	public function unsetUser (Users $user) {
		$this->users->removeElement($user);
	}
	
	public function setUser (Users $user) {
		$this->users[] = $user;
	}

	/* end Languages methods */
	
}