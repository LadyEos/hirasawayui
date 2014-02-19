<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ZfcUser\Entity\UserInterface as User;

/** @ORM\Entity */
class Users implements User {
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id;

    /** @ORM\Column(type="string", length=32, unique=TRUE) */
    protected $username;
    
    /** @ORM\Column(type="string", length=128, nullable=TRUE) */
    protected $displayname;
    
    /** @ORM\Column(type="string", length=255) */
    protected $password;
    
    /** @ORM\Column(type="string", length=255, nullable=TRUE) */
    protected $password_salt;
    
    /** @ORM\Column(type="string", length=128) */
    protected $email;
    
    /** @ORM\Column(type="boolean", length=1) */
    protected $activated;
    
    /** @ORM\Column(type="boolean", length=1) */
    protected $is_admin;
    
    /** @ORM\Column(type="datetime") */
    protected $created;
    
    /** @ORM\Column(type="smallint", nullable=TRUE) */
    protected $state;
    
    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\Songs", inversedBy="users")
     * @ORM\JoinTable(name="SongAuthors",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="song_id", referencedColumnName="id")}
     *      )
     * @ORM\OrderBy({"created" = "DESC"})
     **/
    protected $songs;
    
    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\ProfileTypes",inversedBy="users")
     * @ORM\JoinTable(name="UserProfileTypes",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="profile_id", referencedColumnName="id")}
     *      )
     **/
    protected $profile_types;
    
    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\GroupChats", inversedBy="users")
     * @ORM\JoinTable(name="GroupChatUsers",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_chat_id", referencedColumnName="id")}
     *      )
     **/
    protected $group_chats;
    
    /**
     * @ORM\OneToMany(targetEntity="SoldSongs", mappedBy="users")
     **/
    protected $bought;
    
    /**
     * @ORM\OneToMany(targetEntity="Payments", mappedBy="users")
     **/
    protected $payments;
    
    /**
     * @ORM\OneToMany(targetEntity="SongsVersionHistory", mappedBy="users")
     **/
    protected $versions;
    
    /**
     * @ORM\OneToMany(targetEntity="Likes", mappedBy="users")
     **/
    protected $likes;
    
    /**
     * @ORM\OneToMany(targetEntity="PrivateMessages", mappedBy="sender")
     **/
    protected $senders;
    
    /**
     * @ORM\OneToMany(targetEntity="PrivateMessages", mappedBy="recipient")
     **/
    protected $recipients;
    
    /**
     * @ORM\OneToOne(targetEntity="UserProfiles", inversedBy="user")
     * @ORM\JoinColumn(name="user_profile_id", referencedColumnName="id")
     */
    protected $user_profile;
    
    
    public function __construct(){
    	$this->created = new \DateTime();
    	$this->activated = FALSE;
    	$this->is_admin = FALSE;
    	$this->profile_types = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->group_chats = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->songs = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->bought = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->payments = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->versions = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->likes = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->senders = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->recipients = new \Doctrine\Common\Collections\ArrayCollection();
    	
    }

    // getters/setters
    
    public function getId(){
    	return $this->id;
    }
    
    public function getUsername(){
    	return $this->username;
    }
    
    public function setUsername($username){
    	$this->username = $username;
    }
    
    public function getPassword(){
    	return $this->password;
    }
    
    public function setPassword($password){
    	$this->password = $password;
    }
    
    public function getEmail(){
    	return $this->email;
    }
    
    public function setEmail($email){
    	$this->email = $email;
    }
    
    public function getActivated(){
    	return $this->activated;
    }
    
    public function setActivated($activated){
    	$this->activated = $activated;
    }
    
    public function getCreated(){
    	return $this->created;
    }
    
    public function getProfile_types(){
    	return $this->profile_types;
    }
    
    public function setProfile_types($profile_types){
    	$this->profile_types = $profile_types;
    }
    
    public function getGroup_chats(){
    	return $this->group_chats;
    }
    
    public function setGroup_chats($group_chats){
    	$this->group_chats = $group_chats;
    }
    
    public function getSongs(){
    	return $this->songs;
    }
    
    public function setSongs($songs){
    	$this->songs = $songs;
    }
    
    public function getPayments(){
    	return $this->payments;
    }
    
    public function setPayments($payments){
    	$this->payments = $payments;
    }
    
    public function getBought(){
    	return $this->bought;
    }
    
    public function setBought($bought){
    	$this->bought = $bought;
    }
    
    public function getLikes(){
    	return $this->likes;
    }
    
    public function setLikes($likes){
    	$this->likes = $likes;
    }
    
    public function getVersions(){
    	return $this->versions;
    }
    
    public function setVersions($versions){
    	$this->versions = $versions;
    }
    
    public function getRecipients(){
    	return $this->recipients;
    }
    
    public function setRecipients($recipients){
    	$this->recipients = $recipients;
    }
    
    public function getSenders(){
    	return $this->senders;
    }
    
    public function setSenders($senders){
    	$this->senders = $senders;
    }
    
    public function getDisplayName(){
    	return $this->displayname;
    }
    
    public function setDisplayName($displayName){
    	$this->displayname = $displayName;
    }
    
    public function setId($id){
    	$this->id = $id;
    }
    
    public function getState(){
    	return $this->state;
    }
    
    public function setState($state){
    	$this->state = $state;
    }
    
    public function getUserProfile(){
    	return $this->user_profile;
    }
    
    public function setUserProfile($userprofile){
    	$this->user_profile = $userprofile;
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
    	$this->username = $data['username'];
    	$this->password = $data['password'];
    	$this->email = $data['email'];
    	
    	if(array_key_exists('password_salt', $data))
    	   $this->password_salt = $data['password_salt'];
    	
    	if(array_key_exists('password_salt', $data))
    	   $this->activated = $data['activated'];
    	
    	if(array_key_exists('created', $data))
    	   $this->created = $data['created'];
    	
    	if(array_key_exists('user_profile', $data))
    	   $this->user_profile= $data['user_profile'];
    	
    	if(array_key_exists('is_admin', $data))
    		$this->is_admin= $data['is_admin'];
    	
    	if(array_key_exists('displayname', $data))
    		$this->displayname = $data['displayname'];
    }
    
    public function get(){
    	return $this;
    }
    
    
    
    
    
    /* Collection */
    
    public function hasProfileType(ProfileTypes $profileType) {
    	$profileTypes = array();
    	foreach ($this->getProfile_types() as $arrMember) {
    		$profileTypes[] = $arrMember->getProfile_key();
    	}
    	if (in_array($profileType->getProfile_key(), $profileTypes))    //check if the supplied language is to be removed or not
    		return true;
    	else 
    	    return false;
    }
    
    public function removeProfileType (ProfileTypes $profileType) {
    	$this->profile_types->removeElement($profileType);
    	$profileType->unsetUser($this);
    }
    
    public function addProfileType (ProfileTypes $profileType) {
    	$profileType->setUser($this);
    	$this->profile_types[] = $profileType;
    }
    /* end Languages methods */
    
    public function countProfileTypes(){
    	return $this->profile_types->count();
    }
    
    /* Collection Songs */
    
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
    	$song->unsetUser($this);
    }
    
    public function addSongs (Songs $song) {
    	$song->setUser($this);
    	$this->songs[] = $song;
    }
    /* end Languages methods */
    
    public function countSongs(){
    	return $this->songs->count();
    }
    
    
    
}