<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Payments {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Users", inversedBy="payments")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 **/
	protected $users;

	/** @ORM\Column(type="datetime") */
	protected $date;

	/** @ORM\Column(type="decimal", precision=6, scale=3 ) */
	protected $amount;

	/** @ORM\Column(type="string", length=300) */
	protected $concept;
	
	/**
	 * @ORM\OneToMany(targetEntity="SoldSongs", mappedBy="orders")
	 **/
	protected $songs;

	public function __construct(){
		$this->date = new \DateTime();
		$this->songs = new \Doctrine\Common\Collections\ArrayCollection();
	}

	// getters/setters

	public function getId(){
		return $this->id;
	}

	public function getDate(){
		return $this->date;
	}

	public function getAmount(){
		return $this->amount;
	}

	public function setAmount($amount){
		$this->amount = $amount;
	}

	public function getConcept(){
		return $this->concept;
	}

	public function setConcept($concept){
		$this->concept = $concept;
	}
	
	public function getUsers(){
		return $this->users;
	}
	
	public function setUsers($users){
		$this->users = $users;
	}
	
	public function getSongs(){
		return $this->songs;
	}
	
	public function setSongs($songs){
		$this->songs = $songs;
	}
}