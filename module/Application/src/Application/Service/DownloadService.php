<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Entity\Downloads;
use Application\Entity\Users;
use Application\Entity\Songs;
use ZipArchive;

class DownloadService implements ServiceLocatorAwareInterface{
    
    protected $em;
    protected $entity = 'Application\Entity\Downloads';
    protected $download;
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
    
    public function getDownload(){
        return $this->download;
    }
    
    public function setDownload($download){
    	$this->download = $download;
    }
    
    public function find($id){
        $objectManager = $this->getOMService()->getEntityManager();
         
        return $objectManager->find($this->entity, $id);
    }
    
    public function findAndSet($id){
    	$objectManager = $this->getOMService()->getEntityManager();
    	 
    	$this->download = $objectManager->find($this->entity, $id);
    	
    	return $this->download;
    }
    
    public function findBy($array){
    	$objectManager = $this->getEntityManager();
    	return $objectManager->getRepository($this->entity)->findBy($array);
    }
 
    public function findOneBy($array){
        $objectManager = $this->getOMService()->getEntityManager();
        $user = $objectManager->getRepository($this->entity)->findOneBy($array);
    }
    
    public function query($query,$data){
    	$query = $this->getEntityManager()->createQuery($query);
    
    	foreach ($data as $key => $value){
    		$query->setParameter($key, $value);
    	}
    
    	return $query->getResult();
    }
    
    public function create($file,Songs $song, Users $user){
        $objectManager = $this->getOMService();
        $this->download = new Downloads();
        $this->download->setFile($file);
        $this->download->setKey($this->generateKey());
        $this->download->setSong($song);
        $this->download->setUser($user);

        $objectManager->persist($this->download);
        $objectManager->flush();
        
        return $this->download;
    }
    
    public function createAlbum($album, $user){
    	$objectManager = $this->getOMService();
    	$url = $this->createZipfile($album);
    	
    	if($url != false){
    	    $this->download = new Downloads();
    	    $this->download->setFile($url);
    	    $this->download->setKey($this->generateKey());
    	    $this->download->setAlbum($album);
    	    $this->download->setUser($user);
    	    $objectManager->persist($this->download);
    	    $objectManager->flush();
    	    
    	    return $this->download;
    	}else{
    		return false;
    	}
    }
    
    private function generateKey(){
        //rand()
        //create a random key
        $strKey = md5(microtime());
        
        $array = array('dkey'=>$strKey);
        $result = $this->findOneBy($array);

        //check to make sure this key isnt already in use
        if(sizeof($result) > 0){
        	//key already in use
        	return generateKey();
        }else{
        	//key is OK
        	return $strKey;
        }
    }
    
    public function createZipfile($album){
        /*
         * ZipArchive::CREATE will overwrite any file already there with the same name
         * 
         */

        $files = array();
        foreach ($album->getSongs() as $song){
        	$file = array();
            $file['url'] = $song->getVersions()->first()->getUrl();
            $file['filename'] = substr($song->getVersions()->first()->getUrl(),7);
        	$file['name'] = $song->getName().substr($song->getVersions()->first()->getUrl(),-4);
        	$files[] = $file;
        }
        
        $zipname = $album->getName().'.zip';
        $zip = new ZipArchive;
        $res = $zip->open('./public/uploads/compressed/'.$zipname, ZipArchive::CREATE);
        if($res){
            foreach ($files as $file) {
            	$res2 = $zip->addFile('./public/uploads/'.$file['url'],$file['name']);
            }
            $zip->close();
            return '/compressed/'.$zipname;
        }else{
        	return false;
        }

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