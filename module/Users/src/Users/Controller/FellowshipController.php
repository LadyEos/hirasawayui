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
    protected $actionService;
    protected $songService;
    protected $likeService;
    protected $actionEntity = 'Application\Entity\Actions';
    /**
     * User page
     */
    public function indexAction(){
        
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $this->_view = new ViewModel();
        
            
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
    
    public function feedAction(){
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        $this->_view = new ViewModel();
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        
        $feeds = $this->getActionService()->getFeeds($user);
        $texts = array();
        if(sizeof($feeds) > 0){
            foreach ($feeds as $feed){
            	$text = 'user <a href="'.$this->url()->fromRoute('profile',array('action'=>'index','id'=>$feed->getUser()->getId())).'">'
            			.$feed->getUser()->getUsername(). '</a> ';
            	if($feed->getAction() == 'follow'){
            		$fuser = $this->getUserService()->find($feed->getActionId());
            		$text.= ' followed user <a href="'.$this->url()->fromRoute('profile',array('action'=>'index','id'=>$fuser->getId())).'">'.$fuser->getUsername().'</a> - at '
            				.$feed->getCreated()->format('Y-m-d H:i:s').'<br>';
            		$texts[]=$text;
            	}else if($feed->getAction() == 'bought'){
            		$fsong = $this->getSongService()->find($feed->getActionId());
            		$text.= ' downloaded the song <a href="'.$this->url()->fromRoute('song',array('action'=>'view','id'=>$fsong->getId())).'">'
            				.$fsong->getName().'</a> - at '.$feed->getCreated()->format('Y-m-d H:i:s').'<br>';
            		$texts[]=$text;
            	}else if($feed->getAction() == 'author'){
            		$fsong = $this->getSongService()->find($feed->getActionId());
            		$text.= ' is now collaborating with the project '.$fsong->getName().' - at '.$feed->getCreated()->format('Y-m-d H:i:s').'<br>';
            		$texts[]=$text;
            	}else if($feed->getAction() == 'created project'){
            		$fsong = $this->getSongService()->find($feed->getActionId());
            		$text.= ' created the project '.$fsong->getName().' - at '.$feed->getCreated()->format('Y-m-d H:i:s').'<br>';
            		$texts[]=$text;
            	}else if($feed->getAction() == 'complete project'){
            		$fsong = $this->getSongService()->find($feed->getActionId());
            		$text.= '\'s project <a href="'.$this->url()->fromRoute('song',array('action'=>'view','id'=>$fsong->getId())).'">'.$fsong->getName()
            		.'</a> is now available for download at the store - at '.$feed->getCreated()->format('Y-m-d H:i:s').'<br>';
            		$texts[]=$text;
            	}else if($feed->getAction() == 'like'){
            		$fsong = $this->getSongService()->find($feed->getActionId());
            		$text.= ' liked the project  <a href="'.$this->url()->fromRoute('song',array('action'=>'view','id'=>$fsong->getId())).'">'.$fsong->getName()
            		.'</a> - at '.$feed->getCreated()->format('Y-m-d H:i:s').'<br>';
            		$texts[]=$text;
            	}
            }
            $this->_view->setVariable('feeds', $texts);
        }else{
            $this->_view->setVariable('feeds', false);
        }
      
        
        return $this->_view;
        
    }
    
    public function likeAction(){
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    
    	$user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
    	$song = $this->getSongService()->find($this->params()->fromRoute('id'));
    
    	$like = $this->getLikeService()->create($song, $user);
    	
    	$redirectUrl = $this->getRequest()->getHeader('Referer')->getUri();
    	//$redirectUrl =  $this->getRequest()->getRequestUri();
    	return $this->redirect()->toUrl($redirectUrl);
    	//return $this->redirect()->toRoute('store',array('action'=>'cart'));
    
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
    
    private function getActionService()
    {
    	if (! $this->actionService) {
    		$this->actionService = $this->getServiceLocator()->get('Application\Service\ActionService');
    	}
    
    	return $this->actionService;
    }
    
    private function getSongService()
    {
    	if (!$this->songService) {
    		$this->songService = $this->getServiceLocator()->get('Application\Service\SongService');
    	}
    
    	return $this->songService;
    }
    
    private function getLikeService()
    {
    	if (!$this->likeService) {
    		$this->likeService = $this->getServiceLocator()->get('Application\Service\LikeService');
    	}
    
    	return $this->likeService;
    }
}