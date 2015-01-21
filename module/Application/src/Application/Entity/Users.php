<?php
namespace Application\Entity;

use BjyAuthorize\Provider\Role\ProviderInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ZfcUser\Entity\UserInterface as User;

/** @ORM\Entity */
class Users implements User, ProviderInterface {
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
    
    ///** @ORM\Column(type="boolean", length=1) */
    //protected $is_admin;
    
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
     * @ORM\ManyToMany(targetEntity="Application\Entity\Role",inversedBy="users")
     * @ORM\JoinTable(name="UserRoles",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *      )
     **/
    protected $roles;
    
    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\GroupChats", inversedBy="users")
     * @ORM\JoinTable(name="GroupChatUsers",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_chat_id", referencedColumnName="id")}
     *      )
     **/
    protected $group_chats;
    
    /*/**
     * @ORM\OneToMany(targetEntity="SoldSongs", mappedBy="users")
     **/
    //protected $bought;
    
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
    protected $sentMessages;
    
    /**
     * @ORM\ManyToMany(targetEntity="PrivateMessages", mappedBy="recipient")
     **/
    protected $recipients;
    
    /**
     * @ORM\OneToOne(targetEntity="UserProfiles", inversedBy="user")
     * @ORM\JoinColumn(name="user_profile_id", referencedColumnName="id")
     */
    protected $user_profile;
    
    /**
     * @ORM\OneToOne(targetEntity="BankAccounts", inversedBy="user")
     * @ORM\JoinColumn(name="bank_id", referencedColumnName="id")
     */
    protected $bank;
    
    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\Users", inversedBy="followedBy")
     * @ORM\JoinTable(name="Follow",
     *      joinColumns={@ORM\JoinColumn(name="follow_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="followed_id", referencedColumnName="id")}
     *      )
     **/
    protected $follows;
    
    /**
     * @ORM\ManyToMany(targetEntity="Users", mappedBy="follows")
     */
    protected $followedBy;
    
    /**
     * @ORM\OneToMany(targetEntity="Downloads", mappedBy="user")
     * @ORM\OrderBy({"created" = "DESC"})
     **/
    protected $downloads;
    
    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\Albums", inversedBy="users")
     * @ORM\JoinTable(name="AlbumCollaborators",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="album_id", referencedColumnName="id")}
     *      )
     * @ORM\OrderBy({"created" = "DESC"})
     **/
    protected $albums;
    
    public function __construct(){
    	$this->created = new \DateTime();
    	$this->activated = FALSE;
    	$this->is_admin = FALSE;
    	$this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->group_chats = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->songs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->albums = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->payments = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->versions = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->likes = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->sentMessages = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->recipients = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->follows = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->followedBy = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->downloads = new \Doctrine\Common\Collections\ArrayCollection();
    	
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
    
    public function getRoles(){
    	return $this->roles;
    }
    
    public function setRoles($roles){
    	$this->roles = $roles;
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
    
    public function getBank(){
    	return $this->bank;
    }
    
    public function setBank($bank){
    	$this->bank = $bank;
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
    
    public function getSentMessages(){
    	return $this->sentMessages;
    }
    
    public function setSentMessages($messages){
    	$this->sentMessages = $messages;
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
    
    public function getFollows(){
    	return $this->follows;
    }
    
    public function setFollows($follows){
    	$this->follows = $follows;
    }
    
    public function getFollowedBy(){
    	return $this->followedBy;
    }
    
    public function setFollowedBy($followedBy){
    	$this->followedBy = $followedBy;
    }
    
    public function getAlbums(){
    	return $this->albums;
    }
    
    public function setAlbums($albums){
    	$this->albums = $albums;
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
    	
    	if(array_key_exists('activated', $data))
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
    
    public function hasRole(Role $role) {
    	$roles = array();
    	foreach ($this->getRoles() as $arrMember) {
    		$roles[] = $arrMember->getRole_key();
    	}
    	if (in_array($role->getRole_key(), $roles))    //check if the supplied language is to be removed or not
    		return true;
    	else 
    	    return false;
    }
    
    public function removeRole (Role $role) {
    	$this->roles->removeElement($role);
    	$role->unsetUser($this);
    }
    
    public function addRole (Role $role) {
    	$role->setUser($this);
    	$this->roles[] = $role;
    }
    /* end Languages methods */
    
    public function countRoles(){
    	return $this->roles->count();
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
    
    
    
    
    
    
    public function hasFollower(Users $follower) {
    	$followers = array();
    	foreach ($this->getFollowedBy() as $arrMember) {
    		$followers[] = $arrMember->getId();
    	}
    	if (in_array($follower->getId(), $followers))    //check if the supplied language is to be removed or not
    		return true;
    	else
    		return false;
    }
    
    public function removeFollower (Users $follower) {
    	$this->followedBy->removeElement($follower);
    	//$follower->unfollow($this);
    }
    
    public function addFollower (Users $follower) {
    	//$follower->follow($this);
    	$this->followedBy[] = $follower;
    }
    
    
    public function isFollowedBy(Users $followedBy) {
    	$followers = array();
    	foreach ($this->getFollows() as $arrMember) {
    		$followers[] = $arrMember->getId();
    	}
    	if (in_array($followedBy->getId(), $followers))    //check if the supplied language is to be removed or not
    		return true;
    	else
    		return false;
    }
    
    public function unfollow (Users $user) {
    	if($user->hasFollower($this)){
    	    $user->removeFollower($this);
    	    $this->follows->removeElement($user);
    	}
    }
    
    public function follow (Users $user) {
    	$user->addFollower($this);
        $this->follows[] = $user;
    }
    
    
    private function setUserRole(){
       
        
    }
    
    public function hasAlbum (Albums $album) {
    	$albums = array();
    	foreach ($this->getAlbums() as $arrMember) {
    		$albums[] = $arrMember->getName();
    	}
    	if (in_array($album->getName(), $albums))
    		return true;
    }
    
    public function removeAlbum (Albums $album) {
    	$this->albums->removeElement($album);
    	$album->unsetUser($this);
    }
    
    public function addAlbum (Albums $album) {
    	$album->setUser($this);
    	$this->albums[] = $album;
    }
    
    
    public function removeRecipient (PrivateMessages $message) {
    	$this->recipients->removeElement($message);
    	//$follower->unfollow($this);
    }
    
    public function addRecipient (PrivateMessages $message) {
    	//$follower->follow($this);
    	$this->recipients[] = $message;
    }
    
    
    
    
}