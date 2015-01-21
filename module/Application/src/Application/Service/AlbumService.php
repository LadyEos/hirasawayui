<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Entity\Songs;
use Application\Entity\Albums;
use Application\Entity\Genres;

class AlbumService implements ServiceLocatorAwareInterface{
    
    protected $album;
    protected $em;
    protected $entity = 'Application\Entity\Albums';
    protected $entityGenres = 'Application\Entity\Genres';
    protected $oMService;
    protected $actionService;
    protected $songService;
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
    
    public function create($data,$user){
        $objectManager = $this->getOMService();
        $album = new Albums();
        $actions = array();
        
        $album->populate($data);
        
        foreach ($data['song'] as $song){
            $album->addSong($this->getSongService()->find($song));
        	$actions[] = $song;
        }
        
        $objectManager->persist($album);
        
      
        $user->addAlbum($album);
        $objectManager->flush();
        
        
        $this->getActionService()->create($album->getId(), 'created album', $user);
        
        foreach ($actions as $action){
        $this->getActionService()->create($action, 'added to album', $user,$album->getId());
        }
        
        if($data['completed']){
            $this->getActionService()->create($album->getId(), 'complete album', $user);
            $config = $this->getServiceLocator()->get('config');
            $amount =  $config['MusicLackey']['albumSongPrice'];
            $this->getPriceService()->create(($amount*sizeof($data['song'])),null,$album);
        }
        
        return $album;
        
        
    }
    
    public function edit($data,$user,$album){
        $objectManager = $this->getOMService();
        
        $this->setAlbum($album);
        
        $result = $this->findSongs($data['song']);
        
        $album->addSongs($result['set']);
        $album->removeSongs($result['unset']);
        
        $objectManager->flush();
        
        foreach ($result['set'] as $action){
        	$this->getActionService()->create($action->getId(), 'added to album', $user,$album->getId());
        }
        
        if($data['completed']==1){
        	$this->getActionService()->create($album->getId(), 'complete album', $user);
        	$config = $this->getServiceLocator()->get('config');
        	$amount =  $config['MusicLackey']['albumSongPrice'];
        	$this->getPriceService()->create(($amount*sizeof($data['song'])),null,$album);
        }
        
        return $album;
    }
    
    public function setAlbum($album){
    	$this->album = $album;
    }
    
    public function getAlbum(){
    	return $this->album;
    }
    
    public function countTracks(){
    	return $this->album->getSongs()->count();
    }
    
    public function delete($album){
    	$objectManager = $this->getOMService();
    	$album->setActive(0);
    	$objectManager->flush();
    }
    
    public function test($album){
    	
        echo $album->getId().'<br>';
        echo $album->getName().'<br>';
        echo $album->getDescription().'<br>';
        echo $album->getCreated()->format('Y-m-d').'<br>';
        echo $album->countSongs().'<br>';
        echo '<hr><br>';
        foreach ($album->getSongs() as $song){
        	echo $song->getName().'<br>';
        }
        
        //var_dump($album->getSongs());
    	
    }
    
    public function findSongs($song_ids){
    	$o_songs = $this->album->getSongs();
    	
    	$old_songs = array();
    	foreach ($o_songs as $song){
    		$old_songs[] = $song;
    	}
    	
    	$new_songs = array();
    	foreach($song_ids as $id){
    		$new_songs[] = $this->getSongService()->find($id);
    	}
    	
    	$to_delete = array_udiff($old_songs, $new_songs, function ($a,$b){
    		if($a->getId() <  $b->getId())
    			return 1;
    		else if($a->getId() >  $b->getId())
    			return -1;
    		else
    			return 0;
    	});

    	return array('set'=> $new_songs,'unset'=>$to_delete);
    }
    
    public function playlist($album){
    	$array = array();
    	
    	foreach ($album->getSongs() as $song){
    	    /*if($song->getVersions()->first()->getUrlogg()!=null){
                    $array[] = array('title'=>$song->getName(),
    		            'mp3'=>'/uploads'.$song->getVersions()->first()->getUrl(),
                        'ogg'=>'/uploads'.$song->getVersions()->first()->getUrlogg());
    	    }else{*/
    	        $array[] = array('title'=>$song->getName(),
    	        		'mp3'=>'/uploads'.$song->getVersions()->first()->getUrl());
    	    //}
    	}
    	
    	return $array;
    }
    
    private function getSongService()
    {
    	if (!$this->songService) {
    		$this->songService = $this->getServiceLocator()->get('Application\Service\SongService');
    	}
    
    	return $this->songService;
    }
    
    private function getPriceService()
    {
    	if (!$this->priceService) {
    		$this->priceService = $this->getServiceLocator()->get('Application\Service\PriceService');
    	}
    
    	return $this->priceService;
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