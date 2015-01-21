<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Entity\Users;
use Application\Entity\UserProfiles;
use Application\Entity\BankAccounts;

class ProfileService implements ServiceLocatorAwareInterface{
    
    protected $em;
    protected $entity = 'Application\Entity\UserProfiles';
    protected $profile;
    protected $oMService;
    protected $genreService;
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
    
    public function createProfile($data,$country,$user,$genres){
    	$this->profile = new UserProfiles();
        $objectManager = $this->getOMService()->getEntityManager();
    	$this->profile->populate($data);
    	$this->profile->setCountry($country);
    	$objectManager->persist($this->profile);
    	
    	if (array_key_exists('displayname', $data)) {
    		$user->setDisplayname($data['displayname']);
    	}
    	
    	
    	$result = $this->findGenres($genres);
    	$this->profile->addGenres($result['set']);
    	
    	$user->setUserProfile($this->profile);
    	
    	$objectManager->flush();
    }
    
    public function editProfile($country,$user,$displayName,$genres){
        $objectManager = $this->getOMService()->getEntityManager();
        $this->profile->setCountry($country);
        $user->setDisplayName($displayName);
        
        $result = $this->findGenres($genres);
        $this->profile->addGenres($result['set']);
        $this->profile->removeGenres($result['unset']);
        
        $objectManager->flush();
    }
    
    public function addBank($data,$user,$country = null){
    	$bank = new BankAccounts();
    	$objectManager = $this->getOMService()->getEntityManager();
    	$bank->populate($data);
    	
    	if($country != null)
    	   $bank->setCountry($country);
    	
    	$objectManager->persist($bank);
    	$user->setBank($bank);
    	$objectManager->flush();
    }
    
    public function editBank($bank,$data,$country = null){
    	$objectManager = $this->getOMService()->getEntityManager();
    	$bank->populate($data);
    	if($country != null)
    	   $bank->setCountry($country);
    	$objectManager->persist($bank);
    
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
    
    public function findGenres($genre_ids){
    	
        $old_genres = $this->profile->getGenres()->toArray();
        
        $new_genres = array();
        foreach($genre_ids as $id){
    		$new_genres[] = $this->getGenreService()->find($id);
    	}
 
    	$to_delete = array_udiff($old_genres, $new_genres, function ($a,$b){
    		if($a->getId() <  $b->getId())
    		    return 1;
    		else if($a->getId() >  $b->getId())
    		    return -1;
    		else
    			return 0;
    	});
    	
    	return array('set'=> $new_genres,'unset'=>$to_delete);
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
    
    private function getGenreService()
    {
    	if (! $this->genreService) {
    		$this->genreService = $this->getServiceLocator()->get('Application\Service\GenreService');
    	}
    
    	return $this->genreService;
    }
    
}