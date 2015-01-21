<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Entity\Prices;

class PriceService implements ServiceLocatorAwareInterface{
    
    protected $em;
    protected $entity = 'Application\Entity\Prices';
    protected $price;
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
    
    public function getPrice(){
        return $this->price;
    }
    
    public function setPrice($price){
    	$this->price = $price;
    }
    
    public function find($id){
        $objectManager = $this->getOMService()->getEntityManager();
         
        return $objectManager->find($this->entity, $id);
    }
    
    public function findAndSet($id){
    	$objectManager = $this->getOMService()->getEntityManager();
    	 
    	$this->price = $objectManager->find($this->entity, $id);
    	
    	return $this->price;
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
    
    public function create($amount,$song = null,$album = null){
        $objectManager = $this->getOMService();
        
        if(!$this->exists($album,$song)){
            $price = new Prices();
            $price->setAmount($amount);
            $objectManager->persist($price);
            if($song!= null)
            	$song->addPrice($price);
            if($album!=null)
            	$album->addPrice($price);
            $objectManager->flush();
            
            return $price;
        }else{
        	$this->edit($amount,$song,$album);
        }
    }
    
    public function edit($amount,$song = null,$album = null){
        $objectManager = $this->getOMService();
        $query = array();
        if($song != null)
            $query = array('song'=>$song);
        if($album != null)
            $query = array('album'=>$album);
        
        $price = $this->findOneBy($query);
        $price->setAmount($amount);
        $objectManager->flush();
        
    }
    
    public function exists($album = null,$song = null){
    	if($album != null){
    		$array = array('album' => $album);
    	    
    	}else if($song != null){
    		$array = array('song' => $song);
    	}
    	
    	$result = $this->findOneBy($array);
    	
    	return (sizeof($result) > 0)?true:false;
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