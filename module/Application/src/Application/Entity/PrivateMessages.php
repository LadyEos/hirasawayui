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
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="senders")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     **/
	protected $sender;
	
	/**
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="recipients")
     * @ORM\JoinColumn(name="recipient_id", referencedColumnName="id")
     **/
	protected $recipient;
	
	/** @ORM\Column(type="datetime") */
	protected $sent;
	
	/** @ORM\Column(type="string", length=300)*/
	protected $message;

	public function __construct(){
		$this->sent = new \DateTime();
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
		$this->recipient = $recipientt;
	}
	
	public function getSent(){
		return $this->sent;
	}

	public function getMessage(){
		return $this->message;
	}

	public function setMessage($message){
		$this->message = $message;
	}
}