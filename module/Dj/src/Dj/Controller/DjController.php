<?php
namespace Dj\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Application\Entity\Users;


class DjController extends AbstractActionController
{

    protected $oMService;

    
    public function indexAction()
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