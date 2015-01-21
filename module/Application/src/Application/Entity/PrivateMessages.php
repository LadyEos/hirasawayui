<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class PrivateMessages {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/**
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="sentMessages")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     **/
	protected $sender;
	
	/*
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="recipients")
     * @ORM\JoinColumn(name="recipient_id", referencedColumnName="id")
     **/
	
	/**
	 * @ORM\ManyToMany(targetEntity="Users", inversedBy="recipients")
	 * @ORM\JoinTable(name="Recipients",
	 *      joinColumns={@ORM\JoinColumn(name="message_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="recipient_id", referencedColumnName="id")}
	 *      )
	 **/
	protected $recipient;
	
	/** @ORM\Column(type="datetime") */
	protected $sent;
	
	/** @ORM\Column(type="boolean") */
	protected $opened;
	
	/** @ORM\Column(type="boolean") */
	protected $deleted;
	
	/** @ORM\Column(type="string", length=500)*/
	protected $message;
	
	/** @ORM\Column(type="string", length=100)*/
	protected $subject;

	public function __construct(){
		$this->sent = new \DateTime();
		$this->opened = false;
		$this->deleted = false;
		$this->recipient = new \Doctrine\Common\Collections\ArrayCollection();
	}

	// getters/setters

	public function getId(){
		return $this->id;
	}

	public function getSender(){
		return $this->sender;
	}

	public function setSender($sender){
		$this->sender = $sender;
	}
	
	public function getRecipient(){
		return $this->recipient;
	}
	
	public function setRecipient($recipient){
		$this->recipient = $recipient;
	}
	
	public function getSent(){
		return $this->sent;
	}
	
	public function getOpened(){
		return $this->opened;
	}
	
	public function setOpened($opened){
		$this->opened = $opened;
	}

	public function getMessage(){
		return $this->message;
	}

	public function setMessage($message){
		$this->message = $message;
	}
	
	public function getSubject(){
		return $this->subject;
	}
	
	public function setSubject($subject){
		$this->subject = $subject;
	}
	
	public function getDeleted(){
		return $this->deleted;
	}
	
	public function setDeleted($deleted){
		$this->deleted = $deleted;
	}
	
	
	public function hasRecipient (Users $user) {
		$users = array();
		foreach ($this->getRecipient() as $arrMember) {
			$users[] = $arrMember->getId();
		}
		if (in_array($user->getId(), $users))
			return true;
		else
		    return false;
	}
	
	public function removeRecipient (Users $user) {
		$this->recipient->removeElement($user);
		$user->unsetRecipient($this);
	}
	
	public function addRecipient (Users $user) {
		$user->setRecipient($this);
		$this->recipient[] = $user;
	}
}