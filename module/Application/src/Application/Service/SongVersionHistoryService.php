<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Entity\SongsVersionHistory;
use Application\Service\SongService;


class SongVersionHistoryService implements ServiceLocatorAwareInterface{
    
    protected $songVerisionHistoryService;
    protected $em;
    protected $entity = 'Application\Entity\SongsVersionHistory';
    protected $songService;
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
    
    public function find($id){
    	$objectManager = $this->getServiceLocator()->get('Application\Service\DoctrineOMService');
    	
    	$songVersionHistory = $objectManager->find($this->entity, $id);
    	
    	
    	return $songVersionHistory;
    }
    
    public function create($data,$userId,$songId,$file=null,$url=null){
        
        $objectManager = $this->getServiceLocator()->get('Application\Service\DoctrineOMService');
        $version = new SongsVersionHistory();
        $song = $this->getSongService()->find($songId);
        $old = null;
        
        $number = $song->getVersions()->count();
        if($number > 0){
        	$old = $song->getVersions()->first();
        }
        
        $version->populate($data);
        
        if($file != null && $url != null)
        	$version->setUrl($url . $file['file']['name']);
        else{
            if($old != null && $old->getUrl() != null && $old->getUrl() != '')
                $version->setUrl($old->getUrl());
        }
        
        if(!isset($data['lyrics']) || ($data['lyrics'] == null || $data['lyrics'] == '')){
            if($old != null && $old->getLyrics() != null && $old->getLyrics() != '')
            	$version->setLyrics($old->getLyrics());
        }
        
        
        $user = $this->getUserService()->find($userId);
        $version->setUsers($user);
        $objectManager->persist($version);
        $song->addVersion($version);
        $objectManager->flush();
        
        return $version;
    }
    
    public function editSong($id,$data,$sample=null){
        $objectManager = $this->getServiceLocator()->get('Application\Service\DoctrineOMService');
        
        
        $version->populate($data);
        $objectManager->flush();
    }
    
    private function getSongService()
    {
    	if (!$this->songService) {
    		$this->songService = $this->getServiceLocator()->get('Application\Service\SongService');
    	}
    
    	return $this->songService;
    }
    
    public function getEntityManager()
    {
    	if (null === $this->em) {
    		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	}
    	return $this->em;
    }
    
    private function getUserService()
    {
    	if (!$this->userService) {
    		$this->userService = $this->getServiceLocator()->get('Application\Service\UserService');
    	}
    
    	return $this->userService;
    }
    
}