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


class PaymentController extends AbstractActionController
{
    protected $userService;
    protected $countryService;
    protected $profileService;
    protected $paymentService;
    /**
     * User page
     */
    public function indexAction(){
        
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $this->_view = new ViewModel();
        
        $user = $this->getUserService()->find($this->zfcUserAuthentication()->getIdentity()->getId());
        
        //$this->_view->(APPLICATION_PATH.'/modules/Users/view/users/fellowship/feed');
       
        $this->_view->setVariable('user', $user);
        //$this->_view->setVariable('profile', $profile);
            
        return $this->_view;
    }
    
    public function addAction(){
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $user = $this->getUserService()->find($this->zfcUserAuthentication()->getIdentity()->getId());
        
        $form = new BankForm('bank',$this->getServiceLocator());
        $form->setAttribute('method', 'post');
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
        		
        	$form->setInputFilter(new BankFilter($this->getServiceLocator()));
        	$form->setData($request->getPost());
        
        	if ($form->isValid()) {
        		$data = $form->getData();
        		 
        		if($data['country'] != null){
                    $country = $this->getCountryService()->find($data['country']);
                    $this->getProfileService()->addBank($data,$user,$country);
        		}else
        		    $this->getProfileService()->addBank($data,$user);
        		
        		return $this->redirect()->toRoute('payment',array('action'=>'index'));
        	}
        }
        return array(
        		'form' => $form,
        		'error' => 'there was an error'
        );
        
    }
    
    public function editAction(){
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    
    	$user = $this->getUserService()->find($this->zfcUserAuthentication()->getIdentity()->getId());
    	$bank = $user->getBank();
    	
    	if($bank == null){
    	    $this->flashMessenger()->addMessage('You havent saved any payment options!');
    	    return $this->redirect()->toRoute('payment',array('action'=>'index'));
    	}
    	
    	$form = new BankForm('bank',$this->getServiceLocator());
    	$form->setAttribute('method', 'post');
    	
    	$form->setBindOnValidate(false);
    	$form->bind($bank);
    
    	$request = $this->getRequest();
    
    	if ($request->isPost()) {
    
    		$form->setInputFilter(new BankFilter($this->getServiceLocator()));
    		$form->setData($request->getPost());
    
    		if ($form->isValid()) {
    		    $form->bindValues();
    			$data = $form->getData();
    			
    			if($data['country'] != null){
                    $country = $this->getCountryService()->find($data['country']);
                    $this->getProfileService()->editBank($bank,$data,$country);
    			}else
    			    $this->getProfileService()->editBank($bank,$data);
    			
    			return $this->redirect()->toRoute('payment',array('action'=>'index'));
    		}
    	}
    	return array(
    			'form' => $form,
    			'error' => 'there was an error'
    	);
    
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
    
    private function getPaymentService()
    {
    	if (!$this->paymentService) {
    		$this->paymentService = $this->getServiceLocator()->get('Application\Service\PaymentService');
    	}
    
    	return $this->paymentService;
    }
}