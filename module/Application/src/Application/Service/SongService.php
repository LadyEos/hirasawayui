<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Entity\Songs;
use Application\Entity\SongsVersionHistory;
use Application\Entity\Genres;

class SongService implements ServiceLocatorAwareInterface{
    
    protected $song;
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
    
    public function find($id){
    	$objectManager = $this->getServiceLocator()->get('Application\Service\DoctrineOMService');
    	
    	$song = $objectManager->find('Application\Entity\Songs', $id);
    	
    	
    	return $song;
    }
    
    public function create($data,$user,$sample=false){
        
        $objectManager = $this->getServiceLocator()->get('Application\Service\DoctrineOMService');
        $song = new Songs();
        //$version = new SongsVersionHistory();
        
        $song->populate($data);
        $song->setActive(1);
        
        if($sample != null){
        	$song->setCompleted(1);
        	
        	//$category = $this->getSongCategoriesService()->findCategoryByName('sample');
        	//$song->setCategories($category);
        
        }else{
        	$song->setSample(0);
        	if($data['completed']==1)
        	    $song->setCompleted(1);
        		//$category = $this->getSongCategoriesService()->findCategoryByName('complete');
        	//else
        		//$category = $this->getSongCategoriesService()->findCategoryByName('progress');
        
        	//$song->setCategories($category);

        }

        $genre = $objectManager->find('Application\Entity\Genres', $data['genre']);
        $song->setGenre($genre);
        
        $objectManager->persist($song);
        
        $user->addSongs($song);
        
        $objectManager->flush();
        return $song;
    }
    
    public function editSong($id,$data,$sample=null){
        $objectManager = $this->getServiceLocator()->get('Application\Service\DoctrineOMService');
        $song->populate($data);
        
        if($sample == null){
        	if($data['completed']==1)
        		$category = $this->getSongCategoriesService()->findCategoryByName('complete');
        	else
        		$category = $this->getSongCategoriesService()->findCategoryByName('progress');
        
        	$song->setCategories($category);
        }
        
        $genre = $objectManager->find('Application\Entity\Genres', $data['genre']);
        $song->setGenres($genre);
        $version->populate($data);
        $objectManager->flush();
        
        return $song;
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
    
    public function setSong($song){
    	$this->song = $song;
    }
    
    public function getSong(){
        return $this->song;	
    }
    
    public function countVersions(){
    	return $this->song->getVersions()->count();
    }
    
}