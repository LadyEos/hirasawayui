<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class SoldSongs {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Users", inversedBy="bought")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 **/
	protected $users;
	
	/**
     * @ORM\ManyToOne(targetEntity="Payments", inversedBy="songs")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     **/
    protected $orders;
    
    /**
     * @ORM\ManyToOne(targetEntity="Songs", inversedBy="sold")
     * @ORM\JoinColumn(name="song_id", referencedColumnName="id")
     **/
    protected $songs;

	public function __construct(){
		$this->date = new \DateTime();
	}

	// getters/setters

	public function getId(){
		return $this->id;
	}

	public function getOrders(){
		return $this->orders;
	}
	
	public function setOrders($orders){
		$this->orders = $orders;
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