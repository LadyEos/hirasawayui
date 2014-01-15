<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Application\Entity\Users;
use Application\Entity\UserProfiles;
use Users\Form\ProfileFilter;
use Users\Form\ProfileForm;

use ZfcUser\Controller\UserController as ZfcUser;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Stdlib\Parameters;
use ZfcUser\Service\User as UserService;
use ZfcUser\Options\UserControllerOptionsInterface;


class UsersController extends ZfcUser
{
    
    //const CONTROLLER_NAME    = 'zfcuser';
    /**
     * @var Form
     */
    protected $registerForm;
    protected $oMService;
    
    /**
     * @todo Make this dynamic / translation-friendly
     * @var string
     */
    protected $failedLoginMessage = 'Authentication failed. Please try again.';
    
    /**
     * User page
     */
    public function indexAction()
    {
    	if (!$this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    	return new ViewModel();
    }
    
   
    /**
     * Register new user instead of zfcuser registration
     */
    public function registerAction()
    {
        // if the user is logged in, we don't need to register
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        // if registration is disabled
        if (!$this->getOptions()->getEnableRegistration()) {
            return array('enableRegistration' => false);
        }
        
        $request = $this->getRequest();
        $service = $this->getUserService();
        $form = $this->getRegisterForm();

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }

        $redirectUrl = $this->url()->fromRoute(static::ROUTE_REGISTER)
            . ($redirect ? '?redirect=' . rawurlencode($redirect) : '');
        $prg = $this->prg($redirectUrl, true);

        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
            );
        }

        $post = $prg;
        $user = $service->register($post);

        $redirect = isset($prg['redirect']) ? $prg['redirect'] : null;

        if (!$user) {
            return array(
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
            );
        }

        if ($service->getOptions()->getLoginAfterRegistration()) {
            $identityFields = $service->getOptions()->getAuthIdentityFields();
            if (in_array('email', $identityFields)) {
                $post['identity'] = $user->getEmail();
            } elseif (in_array('username', $identityFields)) {
                $post['identity'] = $user->getUsername();
            }
            $post['credential'] = $post['password'];
            $request->getQuery()->set('redirect','addprofile');
            $request->setPost(new Parameters($post));
            return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));
        }

        // TODO: Add the redirect parameter here...
        return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN) . ($redirect ? '?redirect='. rawurlencode($redirect) : ''));
    }
 
    public function AddProfileAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $objectManager = $this->getOMService()->getEntityManager();
        $user = $objectManager->find('Application\Entity\Users', $this->zfcUserAuthentication()->getIdentity()->getId());
        
        if ($user->getUserProfile()!=null) {
        	return $this->redirect()->toRoute('zfcuser/home');
        }
        
        $form = new ProfileForm();
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $profile = new UserProfiles();
            
            $form->setInputFilter(new ProfileFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();
                //$this->getServiceLocator()->get('Zend\Log')->info($form->getData());
                $res = $profile->populate($form->getData());
                
                
                $objectManager->persist($profile);

                if(array_key_exists('displayname', $data)){
                    $user->setDisplayname($data['displayname']);
                }
                
                $user->setUserProfile($profile);
                $objectManager->persist($user);
                
                $objectManager->flush();
                
                return $this->redirect()->toRoute('zfcuser/home');
            }
        }
        return array(
            'form' => $form,
            'error' =>'there was an error'
        );
    }
    
    public function editAction()
    {
    
    
    	return new ViewModel();
    }
    
    public function testAction()
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