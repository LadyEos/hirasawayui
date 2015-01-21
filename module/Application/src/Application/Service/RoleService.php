<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Entity\Users;
use Application\Entity\Role;

class RoleService implements ServiceLocatorAwareInterface{
    
    protected $em;
    protected $entity = 'Application\Entity\Role';
    protected $role;
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
    
    public function getRole(){
        return $this->role;
    }
    
    public function setRole($role){
    	$this->role = $role;
    }
    
    public function find($id){
        $objectManager = $this->getOMService()->getEntityManager();
         
        return $objectManager->find($this->entity, $id);
    }
    
    public function findAll(){
    	$objectManager = $this->getOMService()->getEntityManager();
    	 //$this->getEntityManager()->getRepository('Album\Entity\Album')->findAll(
    	return $objectManager->getRepository($this->entity)->findAll();
    }
    
    public function query($query,$data){
        $query = $this->getEntityManager()->createQuery($query);
        
        foreach ($data as $key => $value){
            $query->setParameter($key, $value);
        }
        
        return $query->getResult();
    }
    
    public function create($name,$key,$height,$parent=null){
        $objectManager = $this->getOMService();
        $role = new Role();
        $role->setRole_name($name);
        $role->setRole_key($key);
        $role->setRoleId($key);
        $role->setHeight($height);
        
        if($parent != null)
            $role->setParent($parent);
        
        $objectManager->persist($role);
        $objectManager->flush();
        
        return $role;
    }
    
    public function setAsUser(Users $user){
        $objectManager = $this->getOMService();
        $user->addRole($this->find(5));
        $objectManager->flush();
    }
    
    public function buildRoleSelectbox($roles)
    {
    	$select = array();
    	foreach ($roles as $role) {
    		$select[$role->getRole_key()] = $role->getRole_name();
    	}
    
    	return $select;
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