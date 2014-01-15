<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class GroupChatMessages {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="GroupChats", inversedBy="messages")
	 * @ORM\JoinColumn(name="group_chat_id", referencedColumnName="id")
	 **/
	private $group_chats;

	/** @ORM\Column(type="string", length=200) */
    protected $message;
    
    /** @ORM\Column(type="datetime") */
    protected $sent;

	public function __construct(){
		$this->sent = new \DateTime();
	}

	// getters/setters

	public function getId(){
		return $this->id;
	}
	
	public function getGroup_Chats(){
		return $this->group_chats;
	}
	
	public function setGroup_Chats($group_chats){
		$this->group_chats = $group_chats;
	}

	public function getMessage(){
		return $this->message;
	}

	public function setMessage($message){
		$this->Message = $message;
	}

	public function getSent(){
		return $this->sent;
	}

	public function setSent($sent){
		$this->sent = $sent;
	}
}