<?php
namespace Application\Service;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Crypt\Password\Bcrypt;
use Application\Entity;

class AuthService implements ServiceLocatorAwareInterface{
    
    /**
     * Set the service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return GetAccessLevel
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
    	$this->serviceLocator = $serviceLocator;
    	return $this;
    }
    
    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
    	return $this->serviceLocator;
    }
    
	
    public function Authenticate($data){
        $messages = null;
        $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        
        $adapter = $authService->getAdapter();
        $adapter->setIdentityValue($data['username']);
        $adapter->setCredentialValue($data['password']);
        $authResult = $authService->authenticate();
        
        if ($authResult->isValid()) {
        	$identity = $authResult->getIdentity();
        	$authService->getStorage()->write($identity);
        		
        	$time = 1209600; // 14 days 1209600/3600 = 336 hours => 336/24 = 14 days
        
        	if ($data['rememberme']) {
        	   $sessionManager = new \Zend\Session\SessionManager();
        	   $sessionManager->rememberMe($time);
        	}
        	
        	return array(
        		'valid'=>true,
        	    'loggedUser'=>$authService->getIdentity()
        	);
        	
        	
        }
        foreach ($authResult->getMessages() as $message) {
         $messages .= "$message\n";
        }
        
        return array(
            'messages'=>$messages,
        );
    }
    
    public function getLoggedUser(){
        $authenticationService = $this->serviceLocator()->get('Zend\Authentication\AuthenticationService');
        return $authenticationService->getIdentity();
    }
    
    /**
     * Static function for checking hashed password (as required by Doctrine)
     * @param Entity\Account $account The identity object
     * @param string $passwordGiven Password provided by the user, to verify
     * @return boolean If the password was correct or not
     */
    public static function verifyHashedPassword(Entity\Users $account, $passwordGiven)
    {
    	$bcrypt = new Bcrypt;
    	return $bcrypt->verify($passwordGiven, $account->getPassword());
    }
}