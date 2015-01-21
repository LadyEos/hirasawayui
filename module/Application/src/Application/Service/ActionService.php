<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Entity\Actions;

class ActionService implements ServiceLocatorAwareInterface{
    
    protected $em;
    protected $entity = 'Application\Entity\Actions';
    protected $action;
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
    
    public function getAction(){
        return $this->action;
    }
    
    public function setAction($action){
    	$this->action = $action;
    }
    
    public function find($id){
        $objectManager = $this->getOMService()->getEntityManager();
         
        return $objectManager->find($this->entity, $id);
    }
    
    public function findAndSet($id){
    	$objectManager = $this->getOMService()->getEntityManager();
    	 
    	$this->action = $objectManager->find($this->entity, $id);
    	
    	return $this->action;
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
    
    public function create($actionId,$action, $user,$extra = null){
        $objectManager = $this->getOMService();

            $this->action = new Actions();
            $this->action->setAction($action);
            $this->action->setActionId($actionId);
            
            if($extra != null)
                $this->action->setExtra($extra);
            
            $this->action->setUser($user);
            
            $objectManager->persist($this->action);
            $objectManager->flush();
            
            return $this->action;
    }
    
    public function getFeeds($user){
        $follows = $user->getFollows();
        $feeds = array();
        if(sizeof($follows)>0){
            $query = 'SELECT a FROM '.$this->entity.' a WHERE ';
            $options = array();
            
            if(sizeof($follows)==1){
            	$query .= 'a.user = :user';
            	$options['user'] = $follows[0];
            }else if(sizeof($follows)>1){
            	for($i = 0; $i < sizeof($follows);$i++){
            		if($i == sizeof($follows)-1){
            			$query .= 'a.user = :'.'user'.$i;
            			$options['user'.$i] = $follows[$i];
            		}else{
            			$query .= 'a.user = :'.'user'.$i.' Or ';
            			$options['user'.$i] = $follows[$i];
            		}
            	}
            }
            
            $query.=' Order by a.created DESC';
            $feeds = $this->query($query, $options);  
        }
        
        return $feeds;
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