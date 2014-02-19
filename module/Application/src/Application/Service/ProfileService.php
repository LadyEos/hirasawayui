<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Entity\Users;
use Application\Entity\UserProfiles;

class ProfileService implements ServiceLocatorAwareInterface{
    
    protected $em;
    protected $entity = 'Application\Entity\UserProfiles';
    protected $profile;
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
    
    public function getProfile(){
        return $this->profile;
    }
    
    public function setProfile($profile){
    	$this->profile = $profile;
    }
    
    public function find($id){
        $objectManager = $this->getOMService()->getEntityManager();
         
        return $objectManager->find($this->entity, $id);
    }
    
    public function findAndSet($id){
    	$objectManager = $this->getOMService()->getEntityManager();
    	 
    	$this->profile = $objectManager->find($this->entity, $id);
    	
    	return $this->profile;
    }
 
    public function findOneBy($array){
        $objectManager = $this->getOMService()->getEntityManager();
        $user = $objectManager->getRepository($this->entity)->findOneBy($array);
    }
    
    public function saveProfile($data){
        $objectManager = $this->getOMService()->getEntityManager();
        $this->profile->populate($data);
    	$objectManager->flush();
    }
    
    public function createProfile($data,$country,$user){
    	$this->profile = new UserProfiles();
        $objectManager = $this->getOMService()->getEntityManager();
    	$this->profile->populate($data);
    	$this->profile->setCountry($country);
    	$objectManager->persist($this->profile);
    	
    	if (array_key_exists('displayname', $data)) {
    		$user->setDisplayname($data['displayname']);
    	}
    	$user->setUserProfile($this->profile);
    	
    	$objectManager->flush();
    }
    
    public function editProfile($country,$user,$displayName){
        $objectManager = $this->getOMService()->getEntityManager();
        $this->profile->setCountry($country);
        $user->setDisplayName($displayName);
        
        $objectManager->flush();
    }
    
    public function query($query,$data){
    	$query = $this->getEntityManager()->createQuery($query);
    
    	foreach ($data as $key => $value){
    		$query->setParameter($key, $value);
    	}
    
    	return $query->getResult();
    }
    
    public function setProfilePicture($file){
        $objectManager = $this->getOMService()->getEntityManager();
        
        $this->profile->setProfile_picture_url($file);
        $objectManager->flush();
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