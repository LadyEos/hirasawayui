<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Likes {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Songs", inversedBy="likes")
	 * @ORM\JoinColumn(name="song_id", referencedColumnName="id")
	 **/
	protected $songs;

	/**
	 * @ORM\ManyToOne(targetEntity="Users", inversedBy="likes")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 **/
	protected $users;

	public function __construct(){
		$this->date = new \DateTime();
		
	}

	// getters/setters

	public function getId(){
		return $this->id;
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