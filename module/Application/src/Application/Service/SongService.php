<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Entity\Songs;
use Application\Entity\SongsVersionHistory;
use Application\Entity\Genres;

class SongService implements ServiceLocatorAwareInterface{
    
    protected $songCategoriesService;
    protected $em;
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
    
    public function findSong($id){
    	$objectManager = $this->getServiceLocator()->get('Application\Service\DoctrineOMService');
    	
    	$song = $objectManager->find('Application\Entity\Songs', $id);
    	
    	
    	return $song;
    }
    
    public function saveSong($data,$user,$sample=null,$file=null,$url=null){
        
        $objectManager = $this->getServiceLocator()->get('Application\Service\DoctrineOMService');
        $song = new Songs();
        $version = new SongsVersionHistory();
        
        $song->populate($data);
        
        if($sample != null){
        	$song->setCompleted(1);
        	$song->setSample(1);
        	$category = $this->getSongCategoriesService()->findCategoryByName('sample');
        	$song->setCategories($category);
        
        }else{
        	$song->setSample(0);
        	if($data['completed']==1)
        		$category = $this->getSongCategoriesService()->findCategoryByName('complete');
        	else
        		$category = $this->getSongCategoriesService()->findCategoryByName('progress');
        
        	$song->setCategories($category);
        }
        
        
        $genre = $objectManager->find('Application\Entity\Genres', $data['genre']);
        $song->setGenres($genre);
        
        $objectManager->persist($song);
        
        $version->populate($data);
        $version->setSongs($song);
        $version->setUsers($user);
        
        
        if($sample != null)
        	$version->setSampletype($data['type']);
        
        if($file != null && $url != null)
            $version->setUrl($url . $file['file']['name']);
        
        
        $objectManager->persist($version);
        
        $user->addSongs($song);
        
        $objectManager->flush();
    }
    
    private function getSongCategoriesService()
    {
    	if (!$this->songCategoriesService) {
    		$this->songCategoriesService = $this->getServiceLocator()->get('Application\Service\SongCategoriesService');
    	}
    
    	return $this->songCategoriesService;
    }
    
    public function getEntityManager()
    {
    	if (null === $this->em) {
    		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	}
    	return $this->em;
    }
    
}