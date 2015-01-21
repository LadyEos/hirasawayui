<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Entity\Likes;

class LikeService implements ServiceLocatorAwareInterface{
    
    protected $em;
    protected $entity = 'Application\Entity\Likes';
    protected $like;
    protected $oMService;
    protected $actionService;
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
    
    public function getLike(){
        return $this->like;
    }
    
    public function setLike($like){
    	$this->like = $like;
    }
    
    public function find($id){
        $objectManager = $this->getOMService()->getEntityManager();
         
        return $objectManager->find($this->entity, $id);
    }
    
    public function findAndSet($id){
    	$objectManager = $this->getOMService()->getEntityManager();
    	 
    	$this->like = $objectManager->find($this->entity, $id);
    	
    	return $this->like;
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
    
    public function create($song,$user){
        $objectManager = $this->getOMService();
        
        $this->like = new Likes();
        $this->like->setSongs($song);
        $this->like->setUsers($user);
        
        $objectManager->persist($this->like);
        $objectManager->flush();
        
        $this->getActionService()->create($song->getId(), 'like', $user);
        
        return $this->like;
        
        
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