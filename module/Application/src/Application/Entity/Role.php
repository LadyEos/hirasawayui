<?php
namespace Application\Entity;

use BjyAuthorize\Acl\HierarchicalRoleInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
/** @ORM\Entity */
class Role implements HierarchicalRoleInterface{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	
	/** @ORM\Column(type="string", length=30) */
	protected $role_name;

	/** @ORM\Column(type="string", length=3) */
	protected $role_key;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, unique=true, nullable=true)
	 */
	protected $roleId;
	
	/**
	 * @ORM\Column(type="smallint",nullable=true)
	 */
	protected $height;
	
	/**
	 * @var Role
	 * @ORM\ManyToOne(targetEntity="Application\Entity\Role")
	 */
	protected $parent;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Application\Entity\Users", mappedBy="roles")
	 */
	protected $users;
	
	public function __construct() {
		$this->users = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	// getters/setters

	public function getId(){
		return $this->id;
	}

    function getRole_name(){
		return $this->role_name;
	}

	public function setRole_name($role_name){
		$this->role_name = $role_name;
	}

	public function getRole_key(){
		return $this->role_key;
	}

	public function setRole_key($role_key){
		$this->role_key = $role_key;
	}
	
	public function getRoleId()
	{
		return $this->roleId;
	}
	
	public function setRoleId($roleId)
	{
		$this->roleId = (string) $roleId;
	}
	
	public function getParent()
	{
		return $this->parent;
	}
	
	public function setParent(Role $parent)
	{
		$this->parent = $parent;
	}
	
    public function getUsers(){
		return $this->users;
	}

	public function setUsers($users){
		$this->users = $users;
	}
	
	public function getHeight(){
		return $this->height;
	}
	
	public function setHeight($height){
		$this->height = $height;
	}
	
	public function getArrayCopy()
	{
		return get_object_vars($this);
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
}