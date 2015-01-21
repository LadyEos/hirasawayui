<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Actions {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	
	/** @ORM\Column(type="datetime") */
	protected $created;

	/** @ORM\Column(type="string", length=30) */
	protected $action;
	
	/** @ORM\Column(type="smallint", length=30) */
	protected $action_id;
    
	/**
	 * @ORM\ManyToOne(targetEntity="Users")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 **/
	protected $user;
	
	/** @ORM\Column(type="string", length=300, nullable=true) */
	protected $extra;
	
	

	public function __construct() {
		$this->created = new \DateTime();
	}

	// getters/setters

	public function getId(){
		return $this->id;
	}
	
	public function getCreated(){
		return $this->created;
	}

	public function getUser(){
		return $this->user;
	}

	public function setUser($user){
		$this->user = $user;
	}

	public function getAction(){
		return $this->action;
	}

	public function setAction($action){
		$this->action = $action;
	}
	
	public function getActionId(){
		return $this->action_id;
	}
	
	public function setActionId($action_id){
		$this->action_id = $action_id;
	}
	
	public function getExtra(){
		return $this->extra;
	}
	
	public function setExtra($extra){
		$this->extra = $extra;
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
		$this->action = $data['action'];
		$this->action_id = $data['actionId'];
		$this->user = $data['user'];
		if(array_key_exists('extra', $data))
    		$this->extra = $data['extra'];
	}
	
	public function get(){
		return $this;
	}
}