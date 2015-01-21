<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Entity\Users;
use Application\Entity\UserProfiles;
use Application\Entity\Countries;

class UserService implements ServiceLocatorAwareInterface{
    
    protected $profileService;
    protected $roleService;
    protected $em;
    protected $user;
    protected $entity = 'Application\Entity\Users';
    protected $oMService;
    protected $actionService;
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
    
    public function getUser(){
        return $this->user;
    }
    
    public function setUser($user){
    	$this->user = $user;
    }
    
    public function find($id){
        $objectManager = $this->getOMService()->getEntityManager();
         
        return $objectManager->find($this->entity, $id);
    }
    
    public function findAll(){
    	$objectManager = $this->getOMService()->getEntityManager();
    	//$this->getEntityManager()->getRepository('Album\Entity\Album')->findAll(
    	return $objectManager->getRepository($this->entity)->findAll();
    }
    
    public function findAndSetUser($id){
    	$objectManager = $this->getOMService()->getEntityManager();
    	 
    	$this->user = $objectManager->find($this->entity, $id);
    	
    	return $this->user;
    }
    
    public function findByUsername($username){
    	
    }
    
    public function findOneBy($array){
        $objectManager = $this->getOMService()->getEntityManager();
        return $objectManager->getRepository($this->entity)->findOneBy($array);
    }
    
    public function saveUser($data){
    	
    }
    
    
    public function removeRole($role){
        $objectManager = $this->getOMService()->getEntityManager();
        if($this->user->hasRole($role)){
        	$this->user->removeRole($role);
        	$objectManager->flush();
        }
    }
    
    public function addRole($role){
        $objectManager = $this->getOMService()->getEntityManager();
        $this->user->addRole($role);
        $objectManager->flush();
    }
    
    public function getRoles($user){
    	$roles = $user->getRoles();
    	$deletable = array();
    	
    	foreach ($roles as $role){
    		if($role->getHeight() == 9)
    		    $deletable[] =$role;
    	}
    	
    	return $deletable;
    }
    
    public function followUser($followee,$followed){
    	$objectManager = $this->getOMService()->getEntityManager();
    	
    	$objectManager->flush();
    }
    
    public function getSampleSongs(){
        $songs = array();
        foreach($this->user->getSongs() as $song){
    		if($song->getSample() == 1)
    		    $songs[] = $song;
    	}
    	
    	return $songs;
    }
    
    public function getMutuals(Users $user){
    	$follows = $user->getFollows();
    	$followedBy = $user->getFollowedBy();
    	
    	$result = array();
    	
    	foreach ($follows as $f){
    		if($followedBy->contains($f)){
    			$result[] = $f;
    		}
    	}
    	
    	return $result;
    }
    
    public function getProjects(){
    	$songs = array();
    	foreach($this->user->getSongs() as $song){
    		if($song->getCompleted() == 0 && $song->getSample() == 0)
    			$songs[] = $song;
    	}
    	 
    	return $songs;
    }
    
    public function getFinishedProjects(){
        $songs = array();
        foreach($this->user->getSongs() as $song){
        	if($song->getCompleted() == 1 && $song->getSample() == 0)
        		$songs[] = $song;
        }
         
        return $songs;
    }
    
    public function follow($user){
        $objectManager = $this->getOMService()->getEntityManager();
        $this->user->follow($user);
        $objectManager->flush();
        
        $this->getActionService()->create($user->getId(), 'follow', $this->user);
        
    }
    
    public function unfollow($user){
    	$objectManager = $this->getOMService()->getEntityManager();
    	$this->user->unfollow($user);
    	$objectManager->flush();
    }
    
    public function addAuthor($user,$song){
        $objectManager = $this->getOMService()->getEntityManager();
        $user->addSongs($song);
        $objectManager->flush();
        
        $this->getActionService()->create($song->getId(), 'author', $user);
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
    
    private function getActionService()
    {
    	if (! $this->actionService) {
    		$this->actionService = $this->getServiceLocator()->get('Application\Service\ActionService');
    	}
    
    	return $this->actionService;
    }
    
}