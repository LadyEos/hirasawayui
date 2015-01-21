<?php
namespace Messages\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Messages\Form\ComposeForm;
use Messages\Form\ComposeFilter;
use Messages\Form\DeleteForm;


class MessagesController extends AbstractActionController
{

    protected $oMService;
    protected $messageService;
    protected $userService;

    
    public function indexAction()
    {
        
        
        return new ViewModel();
    }
    
    public function inboxAction()
    {
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $user = $this->getUserService()->find($this->zfcUserAuthentication()->getIdentity()->getId());
        
        $this->_view = new ViewModel();
        
        $messages = $this->getMessageService()->getRecievedMessages($user);
        //$messages = array();
        
        
        
        $paginator = new Paginator(new ArrayAdapter($messages));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $config = $this->getServiceLocator()->get('config');
        $paginator->setItemCountPerPage($config['MusicLackey']['paginator']['pages']);
        
        $this->_view->setVariable('paginator',$paginator);
        return $this->_view;
    }
    
    public function sendAction(){
        /**
         * TODO: REPLAY TO MULTIPLE USERS
         */
        
        
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $user = $this->getUserService()->find($this->zfcUserAuthentication()->getIdentity()->getId());
        
        if($this->params()->fromRoute('id') != null){
            $reply = $this->params()->fromRoute('id');
            $form = new ComposeForm('send', $this->getServiceLocator(),$reply);
        }else{
            $form = new ComposeForm('send', $this->getServiceLocator());
        }
        
        $form->setAttribute('method', 'post');
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
        
        	$form->setInputFilter(new ComposeFilter($this->getServiceLocator()));
        	$form->setData($request->getPost());
        
        	if ($form->isValid()) {
        		$data = $form->getData();
        		echo '<br><br><br><br><br>';
        		var_dump($data);
        		
        		 
        		$album = $this->getMessageService()->create($user, $data['users'], $data['body'], $data['subject']);
        		 
        		return $this->redirect()->toRoute('messages',array('action'=>'inbox'));
        	}
        }
        return array(
        		'form' => $form,
        		'error' => 'there was an error'
        );
        
    }
    
    public function viewAction()
    {
    	/**
    	 * TODO: while viewing own messages dont replay
    	 * * foward message?
    	 */
        
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    	
    	$user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
    	
    	$this->_view = new ViewModel();
    	$message = $this->getMessageService()->find($this->params()->fromRoute('id'));
    	
    	$this->_view->setVariable('message', $message);
    	$this->getMessageService()->setAsRead($message);
    
    	return $this->_view;
    
    }
    
    public function deleteAction(){
    
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    
    	$id = $this->params()->fromRoute('id');
    
    	$user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
    	$message = $this->getMessageService()->find($id);
    
    	$form = new DeleteForm('Delete Message');
    
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$form->setData($request->getPost());
    
    		if ($form->isValid()) {
    			$this->getMessageService()->delete($message);
    
    			return $this->redirect()->toRoute('messages',array('action'=>'inbox'));
    		}
    	}
    	return array(
    			'form' => $form,
    			'message' => $message
    	);
    }
    
    public function outboxAction()
    {
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    
    	$user = $this->getUserService()->find($this->zfcUserAuthentication()->getIdentity()->getId());
    
    	$this->_view = new ViewModel();
    
    	$messages = $this->getMessageService()->getSentMessages($user);
    	//$messages = array();
    
    	$paginator = new Paginator(new ArrayAdapter($messages));
    	$paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
    	$config = $this->getServiceLocator()->get('config');
    	$paginator->setItemCountPerPage($config['MusicLackey']['paginator']['pages']);
    
    	$this->_view->setVariable('paginator',$paginator);
    	return $this->_view;
    }
     
    private function getMessageService(){
    	if (!$this->messageService) {
    		$this->messageService = $this->getServiceLocator()->get('Application\Service\MessageService');
    	}
    
    	return $this->messageService;
    }
    
    private function getUserService()
    {
    	if (!$this->userService) {
    		$this->userService = $this->getServiceLocator()->get('Application\Service\UserService');
    	}
    
    	return $this->userService;
    }
}