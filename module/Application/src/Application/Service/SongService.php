<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Config;
use Application\Entity\Songs;
use Application\Entity\SongsVersionHistory;
use Application\Entity\Genres;


class SongService implements ServiceLocatorAwareInterface{
    
    protected $song;
    protected $em;
    protected $entity = 'Application\Entity\Songs';
    protected $entityGenres = 'Application\Entity\Genres';
    protected $oMService;
    protected $actionService;
    protected $priceService;
    
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
    	$objectManager = $this->getOMService();
    	
    	$song = $objectManager->find($this->entity, $id);
    	
    	
    	return $song;
    }
    
    public function findAll(){
    	$objectManager = $this->getOMService()->getEntityManager();
    	//$this->getEntityManager()->getRepository('Album\Entity\Album')->findAll(
    	return $objectManager->getRepository($this->entity)->findAll();
    }
    
    public function findBy($array){
        $objectManager = $this->getEntityManager();
        return $objectManager->getRepository($this->entity)->findBy($array);
    }
    
    public function create($data,$user,$sample=false){
        $boolAction = false;
        $objectManager = $this->getOMService();
        $song = new Songs();
        
        $song->populate($data);
        $song->setActive(1);
        
        if($sample != null){
        	$song->setCompleted(1);
        
        }else{
        	$song->setSample(0);
        	if($data['completed']==1){
        	    $song->setCompleted(1);
        	    $boolAction = true;
        	}
        }

        $genre = $objectManager->find($this->entityGenres, $data['genre']);
        $song->setGenre($genre);
        
        $objectManager->persist($song);
        
        $user->addSongs($song);
        
        $objectManager->flush();
        
        $this->getActionService()->create($song->getId(), 'created project', $user);
        
        $config = $this->getServiceLocator()->get('config');
        $amount =  $config['MusicLackey']['songPrice'];

        $this->getPriceService()->create($amount,$song);
        
        if($boolAction){
            $this->getActionService()->create($song->getId(), 'complete project', $user);
        }
        
        return $song;
    }
    
    public function edit($data,$song){
        $objectManager = $this->getOMService();
        //$this->song->populate($data);
        
        /*if($sample == null || $sample == 0){
        	if($data['completed']==1)
        		$category = $this->getSongCategoriesService()->findCategoryByName('complete');
        	else
        		$category = $this->getSongCategoriesService()->findCategoryByName('progress');
        
        	$song->setCategories($category);
        }*/
        
        $genre = $objectManager->find($this->entityGenres, $data['genre']);
        $song->setGenre($genre);
        
        $objectManager->flush();
        
        if($data['completed']==1){
        	foreach ($song->getUsers() as $user)
                $this->getActionService()->create($song->getId(), 'complete project', $user);
        }
        
        return $this->song;
    }
    
    public function delete($song){
        $objectManager = $this->getOMService();
        $song->setActive(0);
        $objectManager->flush();
    }
    
    public function setComplete($song){
        $objectManager = $this->getOMService();
        $song->setCompleted(1);
        $objectManager->flush();
        foreach ($song->getUsers() as $user)
        	$this->getActionService()->create($song->getId(), 'complete project', $user);
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
    
    private function getSongCategoriesService()
    {
    	if (!$this->songCategoriesService) {
    		$this->songCategoriesService = $this->getServiceLocator()->get('Application\Service\SongCategoriesService');
    	}
    
    	return $this->songCategoriesService;
    }
    
    public function setSongCover($song,$filename){
    	$objectManager = $this->getOMService()->getEntityManager();
    
    	$this->setSong($song);
    	$this->song->setCoverurl($filename);
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
    
    private function getActionService()
    {
    	if (! $this->actionService) {
    		$this->actionService = $this->getServiceLocator()->get('Application\Service\ActionService');
    	}
    
    	return $this->actionService;
    }
    
    private function getPriceService()
    {
    	if (! $this->priceService) {
    		$this->priceService = $this->getServiceLocator()->get('Application\Service\PriceService');
    	}
    
    	return $this->priceService;
    }
}