<?php
namespace Oldish\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Application\Entity\Users;
use Oldish\Form\SigninForm;
use Application\Form\UserFilter;
use Oldish\Form\LoginForm;
use Oldish\Form\LoginFilter;
use Zend\Crypt\Password\Bcrypt;


class OldishController extends AbstractActionController
{

    protected $authService;
    protected $oMService;

    public function loginAction()
    {
       
        $form = new LoginForm();
		$form->get('submit')->setValue('Login');
		
		$error=array(
				'error' => 'Your authentication credentials are not valid',
				'form'	=> $form,
				'messages' => null,
		);

		$request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter(new LoginFilter($this->getServiceLocator()));
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
				$data = $form->getData();			
				
				$service = $this->getAuthService()->Authenticate($data);
				if($service['valid'] === true){
				    return $this->redirect()->toRoute('oldish');
				}else{
				    $error['messages'] = $service['messages'];
				}
				
			}
		}
		return new ViewModel($error);
    }

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
    
    public function signinAction(){
        //if($user = $this->identity()){
            //return $this->redirect()->toRoute('home');
        //}else{
        
            //$objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $objectManager = $this->getOMService()->getEntityManager();
            
            $form = new SigninForm();
            $form->get('submit')->setValue('Sign In');
            
            $request = $this->getRequest();
            if ($request->isPost()) {
            	$user = new Users();
            	
            	$form->setInputFilter(new UserFilter($this->getServiceLocator()));
            	$form->setData($request->getPost());
            	
            	if ($form->isValid()) {
            		$user->populate($form->getData());
            		$user->setPassword($this->hashPassword($user->getPassword()));
            		echo $user->getPassword();
            		$objectManager->persist($user);
            		$objectManager->flush();
            
            		// Redirect to list of albums
            		return $this->redirect()->toRoute('oldish');
            	}
            }
            return array(
            		'form' => $form
            );
        //}
    }
    
    public function logoutAction()
    {
    	
    	$user = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');

    	if ($user->hasIdentity()) {
    	    $user->clearIdentity();
    	    $sessionManager = new \Zend\Session\SessionManager();
    	    $sessionManager->forgetMe();
    	}
    	return $this->redirect()->toRoute('oldish');
    
    }
    
    public function signinSuccessAction(){
    	//send confirmation email
    }
    
    private function getAuthService()
    {
    	if (!$this->authService) {
    		$this->authService = $this->getServiceLocator()->get('Application\Service\AuthService');
    	}
    
    	return $this->authService;
    }
    
    private function getOMService()
    {
    	if (!$this->oMService) {
    		$this->oMService = $this->getServiceLocator()->get('Application\Service\DoctrineOMService');
    	}
    
    	return $this->oMService;
    }
    
    private function hashPassword($password){
    	
        $bcrypt = new Bcrypt();
        $bcrypt->setCost(16);
        return $bcrypt->create($password);
        
    }
}