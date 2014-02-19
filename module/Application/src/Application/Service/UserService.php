<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Entity\Users;
use Application\Entity\UserProfiles;
use Application\Entity\Countries;

class UserService implements ServiceLocatorAwareInterface{
    
    protected $profileService;
    protected $em;
    protected $user;
    protected $entity = 'Application\Entity\Users';
    protected $oMService;
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
    
    public function removeProfileType($profileType){
        $objectManager = $this->getOMService()->getEntityManager();
        if($this->user->hasProfileType($profileType)){
        	$this->user->removeProfileType($profileType);
        	$objectManager->flush();
        }
    }
    
    public function addProfileType($profileType){
        $objectManager = $this->getOMService()->getEntityManager();
        $this->user->addProfileType($profileType);
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
    
    
    
}