<?php
namespace Workspace\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Application\Entity\Users;
use Workspace\Form\SampleForm;
use Workspace\Form\SampleFilter;
use Workspace\Form\SampleLyricsForm;
use Workspace\Form\SampleLyricsFilter;
use Application\Entity;
use Application\Entity\SongsVersionHistory;
use Application\Entity\Songs;
use Zend\File\Transfer\Adapter\Http;

class WorkspaceController extends AbstractActionController
{

    protected $userService;

    public function indexAction()
    {
        return new ViewModel();
    }

    public function workspaceAction()
    {
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        
        if($user->getProfile_types()->first()->getProfile_key() =='B')
            return $this->redirect()->toRoute('zfcuser/home');
        
        $this->_view = new ViewModel();
        $profileTypes = $user->getProfile_types()->toArray();
        $sampleSongs =  $this->getUserService()->getSampleSongs();
        $projects= $this->getUserService()->getProjects();
        $finishedProjects= $this->getUserService()->getFinishedProjects();
         
        $this->_view->setVariable('profileTypes', $profileTypes);
        $this->_view->setVariable('samples',$sampleSongs);
        $this->_view->setVariable('projects', $projects);
        $this->_view->setVariable('finishedProjects', $finishedProjects);
        
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

    private function getUserService()
    {
    	if (!$this->userService) {
    		$this->userService = $this->getServiceLocator()->get('Application\Service\UserService');
    	}
    
    	return $this->userService;
    }
}