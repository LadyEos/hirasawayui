<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Entity\Actions;
use Application\Entity\PrivateMessages;

class MessageService implements ServiceLocatorAwareInterface{
    
    protected $em;
    protected $entity = 'Application\Entity\PrivateMessages';
    protected $message;
    protected $oMService;
    protected $userService;
    /**
     * Set the service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return GetAccessLevel
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
    	$this->serviceLocator = $serviceLocator;
    	return $this;
    }
    
    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
    	return $this->serviceLocator;
    }
    
    public function getMessage(){
        return $this->message;
    }
    
    public function setMessage($message){
    	$this->message = $message;
    }
    
    public function find($id){
        $objectManager = $this->getOMService()->getEntityManager();
         
        return $objectManager->find($this->entity, $id);
    }
    
    public function findAndSet($id){
    	$objectManager = $this->getOMService()->getEntityManager();
    	 
    	$this->message = $objectManager->find($this->entity, $id);
    	
    	return $this->message;
    }
    
    public function findBy($array){
    	$objectManager = $this->getEntityManager();
    	return $objectManager->getRepository($this->entity)->findBy($array);
    }
 
    public function findOneBy($array){
        $objectManager = $this->getOMService()->getEntityManager();
        $user = $objectManager->getRepository($this->entity)->findOneBy($array);
    }
    
    public function query($query,$data){
    	$query = $this->getEntityManager()->createQuery($query);
    
    	foreach ($data as $key => $value){
    		$query->setParameter($key, $value);
    	}
    
    	return $query->getResult();
    }
    
    public function create($sender,$recipient,$message,$subject){
        $objectManager = $this->getOMService();
        $recipients = $this->recipientArray($recipient);

            $this->message = new PrivateMessages();
            $this->message->setSender($sender);
            $this->message->setRecipient($recipients);
            $this->message->setMessage($message);
            $this->message->setSubject($subject);
            
            
            $objectManager->persist($this->message);
            $objectManager->flush();
            
            return $this->message;
    }
    
    public function delete($message){
        $objectManager = $this->getOMService();
        $message->setDeleted(true);
        $objectManager->flush();
        
    }
    
    public function setAsRead(PrivateMessages $message){
        $objectManager = $this->getOMService();
        $message->setOpened(true);
        $objectManager->flush();
    }
    
    
    public function getRecievedMessages($user){
        
        $messages = array();
        
        foreach ($user->getRecipients() as $rec){
        	$messages[] = $rec;
        }
        
        return $messages;
    }
    
    public function getSentMessages($user){
    
    	$messages = $this->findBy(array('sender'=>$user));
    
    	return $messages;
    }
    
    private function recipientArray($users){
    	$recipients= array();
    	
        foreach ($users as $userId){
        	$recipients[] = $this->getUserService()->find($userId);
        }
        
        return $recipients;
    }
    
    public function getEntityManager()
    {
    	if (null === $this->em) {
    		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	}
    	return $this->em;
    }
    
    private function getOMService()
    {
    	if (! $this->oMService) {
    		$this->oMService = $this->getServiceLocator()->get('Application\Service\DoctrineOMService');
    	}
    
    	return $this->oMService;
    }
    
    private function getUserService()
    {
    	if (!$this->userService) {
    		$this->userService = $this->getServiceLocator()->get('Application\Service\UserService');
    	}
    
    	return $this->userService;
    }
    
}