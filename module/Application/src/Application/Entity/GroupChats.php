<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class GroupChats {
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id;
    
    /** @ORM\Column(type="datetime") */
    protected $created;
    
    /** @ORM\Column(type="boolean") */
    protected $active;
    
    /**
     * @ORM\OneToMany(targetEntity="GroupChatMessages", mappedBy="group_chats")
     **/
    protected $messages;
    
    /**
     * @ORM\ManyToMany(targetEntity="Users", mappedBy="group_chats")
     */
    protected $users;
    
    public function __construct()
    {
    	$this->created = new \DateTime();
    	$this->messages = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    // getters/setters
    
	public function getId(){
    	return $this->id;
    }
    
	public function getCreated(){
    	return $this->created;
    }
    
	public function getActive(){
    	return $this->active;
    }
    
	public function setActive($active){
    	$this->active = $active;
    }
    
	public function getMessages(){
    	return $this->messages;
    }
    
	public function setMessages($messages){
    	$this->messages = $messages;
    }
}