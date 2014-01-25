<?php
namespace Workspace\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Application\Entity\Users;


class WorkspaceController extends AbstractActionController
{

    protected $oMService;

    
    public function indexAction()
    {
        $objectManager = $this->getOMService()->getEntityManager();
        $repository = $objectManager->getRepository('Application\Entity\Users');
        $allusers = $repository->findAll();

        return new ViewModel(array(
        		'users' => $allusers
        ));
        
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