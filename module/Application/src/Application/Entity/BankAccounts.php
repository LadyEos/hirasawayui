<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity */
class BankAccounts {
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id;

    /** @ORM\Column(type="string", nullable=TRUE) */
    protected $bankname;
    
    /** @ORM\Column(type="string", length=128, nullable=TRUE) */
    protected $account;
    
    /** @ORM\Column(type="string", length=255, nullable=TRUE) */
    protected $paypal;
    
    /** @ORM\Column(type="string", length=255, nullable=TRUE) */
    protected $cardholdername;
    
    /** @ORM\Column(type="string", length=255, nullable=TRUE) */
    protected $name;
    
    /** @ORM\Column(type="string", length=128,nullable=TRUE) */
    protected $email;

    /** @ORM\Column(type="datetime") */
    protected $created;
    
    /** @ORM\Column(type="smallint",nullable=TRUE) */
    protected $payment;
    
    /**
     * @ORM\ManyToOne(targetEntity="Countries", inversedBy="banks")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     */
    protected $country;
    
    /**
     * @ORM\OneToOne(targetEntity="Users", mappedBy="bank")
     */
    private $user;
    
    public function __construct(){
    	$this->created = new \DateTime();
    }

    // getters/setters
    
    public function getId(){
    	return $this->id;
    }
    
    public function getAccount(){
    	return $this->account;
    }
    
    public function setAccount($account){
    	$this->account = $account;
    }
    
    public function getBankname(){
    	return $this->bankname;
    }
    
    public function setBankname($bankname){
    	$this->bankname = $bankname;
    }
    
    public function getEmail(){
    	return $this->email;
    }
    
    public function setEmail($email){
    	$this->email = $email;
    }
    
    public function getCreated(){
    	return $this->created;
    }
    
    public function getCountry(){
    	return $this->country;
    }
    
    public function setCountry($country){
    	$this->country = $country;
    }
    
    public function getName(){
    	return $this->name;
    }
    
    public function setName($name){
    	$this->name = $name;
    }
    
    public function getPaypal(){
    	return $this->paypal;
    }
    
    public function setPaypal($paypal){
    	$this->paypal = $paypal;
    }
    
    public function getUser(){
    	return $this->user;
    }
    
    public function setUser($user){
    	$this->user = $user;
    }
    
    public function getCardholdername(){
    	return $this->cardholdername;
    }
    
    public function setCardholdername($cardholdername){
    	$this->cardholdername = $cardholdername;
    }
    
    public function getPayment(){
    	return $this->payment;
    }
    
    public function setPayment($payment){
    	$this->payment = $payment;
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
    	if(array_key_exists('account', $data))
    	   $this->account = $data['account'];
    	
    	if(array_key_exists('bankname', $data))
    	   $this->bankname = $data['bankname'];
    	
    	if(array_key_exists('country', $data))
    	   $this->country = $data['country'];
    	
    	if(array_key_exists('email', $data))
    	   $this->email= $data['email'];
    	
    	if(array_key_exists('name', $data))
    		$this->name= $data['name'];
    	
    	if(array_key_exists('paypal', $data))
    		$this->paypal = $data['paypal'];
    	
    	if(array_key_exists('user', $data))
    		$this->user = $data['user'];
    	
    	if(array_key_exists('payment', $data))
    		$this->payment = $data['payment'];
    	
    	if(array_key_exists('cardholdername', $data))
    		$this->cardholdername = $data['cardholdername'];
    }
    
    public function get(){
    	return $this;
    }
    

    
}