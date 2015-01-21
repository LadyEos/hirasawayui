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
use Users\Form\BankForm;
use Users\Form\BankFilter;

use Zend\Http\Request;
use Zend\Session\Container;


class ProfileController extends AbstractActionController
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
        $mutuals = $this->getUserService()->getMutuals($user);
        
        //$this->_view->(APPLICATION_PATH.'/modules/Users/view/users/fellowship/feed');
       
        $this->_view->setVariable('user', $user);
        $this->_view->setVariable('profile', $profile);
        $this->_view->setVariable('samples',$sampleSongs);
        $this->_view->setVariable('projects', $projects);
        $this->_view->setVariable('finishedProjects', $finishedProjects);
        $this->_view->setVariable('mutuals', $mutuals);
            
        return $this->_view;
    }
    
    public function addAction()
    {
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    
    	$user = $this->getUserService()->find($this->zfcUserAuthentication()->getIdentity()->getId());
    	$this->getUserService()->setUser($user);
    
    	if ($user->getUserProfile() != null) {
    		return $this->redirect()->toRoute('zfcuser/home');
    	}
    
    	$form = new ProfileForm('profile',$this->getServiceLocator());
    	$form->setAttribute('method', 'post');
    
    	$request = $this->getRequest();
    
    	if ($request->isPost()) {
    	    
    		$form->setInputFilter(new ProfileFilter($this->getServiceLocator()));
    		$form->setData($request->getPost());
    
    		if ($form->isValid()) {
    			$data = $form->getData();
    			//$this->getServiceLocator()->get('Zend\Log')->info($form->getData());
    			
    			$country = $this->getCountryService()->find($data['country']);
    			$this->getProfileService()->createProfile($data,$country,$user,$data['genres']);
    
    			return $this->redirect()->toRoute('avatar',array('action'=>'upload'));
    		}
    	}
    	return array(
    			'form' => $form,
    			'error' => 'there was an error'
    	);
    }
    
    public function editAction()
    {
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
        $id = $this->zfcUserAuthentication()->getIdentity()->getId();
    	$user = $this->getUserService()->find($id);
    
    	if ($user->getUserProfile() == null) {
    		return $this->redirect()->toRoute('zfcuser/addprofile');
    	}

    	$profile = $user->getUserProfile();
    	$this->getProfileService()->setProfile($profile);
    
    	$form = new ProfileForm('profile',$this->getServiceLocator());
    
    	$form->setBindOnValidate(false);
    	$form->bind($profile);
    
    
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$form->setInputFilter(new ProfileFilter($this->getServiceLocator()));
    		$form->setData($request->getPost());
    		if ($form->isValid()) {
    			$form->bindValues();
    
    			$data = array_merge_recursive(
    					$this->getRequest()->getPost()->toArray());
    			
    			//$this->getServiceLocator()->get('Zend\Log')->info($data['genres']);

    			$country = $this->getCountryService()->find($data['country']);
    			
    			$this->getProfileService()->editProfile($country,$user,$form->getData()->getDisplayName(),$data['genres']);
    			return $this->redirect()->toRoute('zfcuser/home');
    		}
    	}
    
    	return array(
    			'id' => $id,
    			'form' => $form,
    			'roles' => $user->getRoles()->toArray()
    	);
    }
    
    /* public function followAction()
    {
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    
    	$logId = $this->zfcUserAuthentication()->getIdentity()->getId();
    	$user = $this->getUserService()->find($logId);
    
    	$id = $this->params()->fromRoute('id');
    
    	$this->getAppUserService()->setUser($user);
    	$this->getAppUserService()->follow( $this->getAppUserService()->find($id));
    
    	return $this->redirect()->toRoute('profile', array(
    			'action' => 'view',
    			'id'=>$logId
    	));
    }
    
    public function unfollowAction(){
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    
    	$user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
    
    	$id = $this->params()->fromRoute('id');
    
    	$this->getAppUserService()->setUser($user);
    	$this->getAppUserService()->unfollow( $this->getAppUserService()->find($id));
    
    	return $this->redirect()->toRoute('profile', array(
    			'action' => 'view',
    			'id'=>$id
    	));
    } */
    

    
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