<?php
// 'Client.php' in '/library/App/Paypal'
namespace Search\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class SearchService implements ServiceLocatorAwareInterface
{
	protected $search;
	protected $em;
	const downloadEntity = 'Application\Entity\Downloads';
	const likeEntity = 'Application\Entity\Likes';
	const songEntity = 'Application\Entity\Songs';
	const userEntity = 'Application\Entity\Users';
	const albumEntity = 'Application\Entity\Albums';
	const userProfileEntity = 'Application\Entity\UserProfiles';
	const genreEntity = 'Application\Entity\Genres';
	const countryEntity = 'Application\Entity\Countries';
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
	
	public function getDownload(){
		return $this->search;
	}
	
	public function setDownload($download){
		$this->download = $download;
	}
	
	public function find($id,$entity){
		$objectManager = $this->getOMService()->getEntityManager();
		 
		return $objectManager->find($entity, $id);
	}
	
	public function findAndSet($id,$entity){
		$objectManager = $this->getOMService()->getEntityManager();
	
		$this->country = $objectManager->find($entity, $id);
		 
		return $this->country;
	}
	
	public function findBy($array,$entity){
		$objectManager = $this->getEntityManager();
		return $objectManager->getRepository($entity)->findBy($array);
	}
	
	public function findOneBy($array,$entity){
		$objectManager = $this->getOMService()->getEntityManager();
		$user = $objectManager->getRepository($entity)->findOneBy($array);
	}
	
	public function query($query,$data,$limit = null){
		$query = $this->getEntityManager()->createQuery($query);
	
		foreach ($data as $key => $value){
			$query->setParameter($key, $value);
		}
		
		if($limit!=null){
			$query->setMaxResults($limit);
		}
		
		return $query->getResult();
	
	}
	
	public function searchUser($conditions){
	    if(array_key_exists('genre', $conditions)){
	    
	       if(sizeof($conditions) ==  1){
	           $query = 'SELECT u FROM '.STATIC::userEntity.'  u JOIN u.user_profile p
	               JOIN p.genres g WHERE g.id = :genre';
	       }else{
	           $query = 'SELECT u FROM '.STATIC::userEntity.'  u JOIN u.user_profile p
	               JOIN p.genres g WHERE (u.username LIKE :username OR u.displayname LIKE :displayname 
	               OR u.email LIKE :email OR p.first_name LIKE :name OR p.last_name LIKE :name2) AND 
	               g.id = :genre';   
	       }
	    }else{
	        $query = 'SELECT u FROM '.STATIC::userEntity.'  u JOIN u.user_profile p
	               WHERE u.username LIKE :username OR u.displayname LIKE :displayname OR u.email LIKE :email
	               OR p.first_name LIKE :name OR p.last_name LIKE :name2';
	    }
	    
	    /*echo '<br><br><br><br><br><br><br>';
	    var_dump($conditions);
	    echo $query;*/
	    return $this->query($query,$conditions);
	}
		
    public function searchSong($conditions,$mode=null){
	    
        /*
         * MODE 1 / null - genre whole word search
         * MODE 2 - genre partial word search
         */
        
        
        
        if(array_key_exists('genre', $conditions)){
            if($mode == null || $mode == 1){
                if(sizeof($conditions) ==  1){
    	           $query = 'SELECT s FROM '.STATIC::songEntity.' s JOIN s.genre g
                        WHERE g.id = :genre';
    	       }else{
    	           $query = 'SELECT s FROM '.STATIC::songEntity.' s JOIN s.genre g
    	               WHERE (s.name LIKE :name OR s.description LIKE :description) 
    	               AND g.id = :genre';
    	       }
	       }else{
	           $genre = $this->getGenreService()->find($conditions['genre']);
	           if(sizeof($conditions) ==  1){
	               $query = 'SELECT s FROM '.STATIC::songEntity.' s JOIN s.genre g
                        WHERE g.name LIKE :genre';
	           }else{
	               $query = 'SELECT s FROM '.STATIC::songEntity.' s JOIN s.genre g
    	               WHERE (s.name LIKE :name OR s.description LIKE :description) 
	                   AND g.name LIKE :genre';
	           }
	           $conditions['genre'] = '%'.$genre->getName().'%';
	       }
	    }else{
	        $query = 'SELECT s FROM '.STATIC::songEntity.' s 
	            WHERE (s.name LIKE :name OR s.description LIKE :description)';   
        }
	    
	    /*
	    echo '<br><br><br><br><br><br><br>';
	    var_dump($conditions);
	    echo $query;
	    //*/
	    return $this->query($query,$conditions);
	}
	
	public function searchAlbum($conditions){
		
		$query = 'SELECT a FROM '.STATIC::albumEntity.' a
               WHERE a.name LIKE :name OR a.description LIKE :description';
		
		/*echo '<br><br><br><br><br><br><br>';
		 var_dump($conditions);
		echo $query;*/
		return $this->query($query,$conditions);
	}
	
	public function searchSongByGenre($conditions){
		$query = 'SELECT s FROM '.STATIC::songEntity.' s JOIN '.STATIC::genreEntity
		.' g ON s.genre_id = g.id WHERE s.completed = 1 AND g.name LIKE %:genre%';
		
		return $this->query($query,$conditions);
	}
	
	public function searchUserByCountry($conditions){
		$query = 'SELECT u FROM '.STATIC::userEntity.' s JOIN '.STATIC::userProfileEntity
		  .' p ON u.user_profile_id = p.id JOIN '.STATIC::countryEntity.' c ON p.country_id = c.id '
		  .'WHERE c.country_name LIKE %:country%';
	
		return $this->query($query,$conditions);
	}
	
	public function playlist($results){
		$array = array();
			
		foreach ($results as $song){
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
	
	public function topDownloaded($num){
		
	    $query = 'SELECT d,count(d.song) FROM '.STATIC::downloadEntity.' d GROUP by d.song';
	    
	    return $this->query($query,array(),$num);
	}
	
	public function topLiked($num){
	
		$query = 'Select l,count(l.songs) from '.STATIC::likeEntity
		.' l where l.songs in (select s FROM '.STATIC::songEntity.' s where s.completed = 1) group by l.songs';
		return $this->query($query,array(),$num);
	}
	
	public function topUnfinished($num){
	
		$query = 'Select l,count(l.songs) from '.STATIC::likeEntity
		.' l where l.songs in (select s FROM '.STATIC::songEntity.' s where s.completed = 0) group by l.songs';
		return $this->query($query,array(),$num);
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
		if (!$this->genreService) {
			$this->genreService = $this->getServiceLocator()->get('Application\Service\GenreService');
		}
	
		return $this->genreService;
	}

}