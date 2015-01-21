<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Entity;
use Doctrine\ORM\EntityManager;

class SongCategoriesService implements ServiceLocatorAwareInterface{
    
    protected $em;
    protected $category;
    protected $oMService;
    protected $entity = 'Application\Entity\SongCategories';
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
    
    public function getCategory(){
    	return $this->category;
    }
    
    public function setCategory($category){
    	$this->category = $category;
    }
    
    public function findCategory($id){
    	$objectManager = $this->getOMService();
    	
    	return $objectManager->find($this->entity, $id);
    }
    
    public function findCategoryByName($key){
    	$query = $this->getEntityManager()->createQuery('SELECT sc FROM '.$this->entity.' sc 
    				        WHERE sc.category_name = :key');
    	$query->setParameter('key', $key);
    	$category = $query->getResult();
    
    	return $category[0];
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