<?php
namespace Search\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;



class SearchController extends AbstractActionController
{

    protected $oMService;

    

    public function indexAction()
    {
        
        return new ViewModel();
    }
    
    public function userAction()
    {
    
    	return new ViewModel();
    }
    
    public function projectAction()
    {
    
    	return new ViewModel();
    }
    
    private function getOMService()
    {
    	if (!$this->oMService) {
    		$this->oMService = $this->getServiceLocator()->get('Application\Service\DoctrineOMService');
    	}
    
    	return $this->oMService;
    }
    
   
}