<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Application\Entity\Users;
use Application\Entity\UserProfiles;
use ZfcUser\Service\User as UserService;
use Users\Form\ProfileFilter;
use Users\Form\ProfileForm;

use Zend\Http\Request;
use Zend\Session\Container;


class FellowshipController extends AbstractActionController
{
    protected $userService;
    protected $countryService;
    protected $profileService;
    /**
     * User page
     */
    public function indexAction(){
        
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $this->_view = new ViewModel();
        
        $logId = $this->zfcUserAuthentication()->getIdentity()->getId();
        $logUser = $this->getUserService()->find($logId);
        
        $id = $this->params()->fromRoute('id');
        $username = $this->params()->fromRoute('username');
        
        if($id != null){
            $user = $this->getUserService()->find($id);
        }else{
            if($username != null){
            	//$this->getServiceLocator()->get('Zend\Log')->info($username);
            	$user = $this->getUserService()->findOneBy(array('username' => $username));
            }else{
            	$user = $logUser;
            }
        } 
        $profile = $user->getUserProfile();
        $this->getUserService()->setUser($user);
        
        $sampleSongs =  $this->getUserService()->getSampleSongs();
        $projects= $this->getUserService()->getProjects();
        $finishedProjects= $this->getUserService()->getFinishedProjects();
       
        $this->_view->setVariable('user', $user);
        $this->_view->setVariable('profile', $profile);
        $this->_view->setVariable('samples',$sampleSongs);
        $this->_view->setVariable('projects', $projects);
        $this->_view->setVariable('finishedProjects', $finishedProjects);
            
        return $this->_view;
    }
    
    
    public function followAction()
    {
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    
    	$logId = $this->zfcUserAuthentication()->getIdentity()->getId();
    	$user = $this->getUserService()->find($logId);
    
    	$id = $this->params()->fromRoute('id');
    
    	$this->getUserService()->setUser($user);
    	$this->getUserService()->follow( $this->getUserService()->find($id));
    
    	return $this->redirect()->toRoute('profile', array(
    			'action' => 'index',
    			'id'=>$id
    	));
    }
    
    public function unfollowAction(){
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    
    	$user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
    
    	$id = $this->params()->fromRoute('id');
    
    	$this->getUserService()->setUser($user);
    	$this->getUserService()->unfollow( $this->getUserService()->find($id));
    
    	return $this->redirect()->toRoute('profile', array(
    			'action' => 'index',
    			'id'=>$id
    	));
    }
    
    public function add(){
    	
    }
    
    public function remove(){
    	 
    }
    
    private function getUserService()
    {
    	if (!$this->userService) {
    		$this->userService = $this->getServiceLocator()->get('Application\Service\UserService');
    	}
    
    	return $this->userService;
    }
    
    private function getCountryService()
    {
    	if (!$this->countryService) {
    		$this->countryService = $this->getServiceLocator()->get('Application\Service\CountryService');
    	}
    
    	return $this->countryService;
    }
    
    private function getProfileService()
    {
    	if (!$this->profileService) {
    		$this->profileService = $this->getServiceLocator()->get('Application\Service\ProfileService');
    	}
    
    	return $this->profileService;
    }
}