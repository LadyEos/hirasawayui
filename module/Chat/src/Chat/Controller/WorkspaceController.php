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
        
        
        return new ViewModel();
    }
    
    public function workspaceAction()
    {
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $objectManager = $this->getOMService()->getEntityManager();
        $id = $this->zfcUserAuthentication()->getIdentity()->getId();
        $user = $objectManager->find('Application\Entity\Users', $id);
        
        $this->_view = new ViewModel();
        $roles = $user->getRoles()->toArray();
        
        $this->_view->setVariable('roles', $roles);
    	return $this->_view;
    }
    
    public function searchAction()
    {
    
    
    	return new ViewModel();
    }
    
    public function postAction()
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