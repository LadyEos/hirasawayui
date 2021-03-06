<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Application\Entity\Users;
use Application\Entity\Role;
use ZfcUser\Controller\UserController as ZfcUser;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Stdlib\Parameters;
use ZfcUser\Service\User as UserService;
use Zend\Http\Request;
use Zend\Session\Container;
use Users\Form\RoleForm;

class UsersController extends ZfcUser
{
    
    // const CONTROLLER_NAME = 'zfcuser';
    /**
     *
     * @var Form
     */
    protected $registerForm;

    protected $userService;

    protected $roleService;

    protected $appUserService;
    
    protected $roleEntity = 'Application\Entity\Role';

    /**
     *
     * @todo Make this dynamic / translation-friendly
     * @var string
     */
    protected $failedLoginMessage = 'Authentication failed. Please try again.';

    /**
     * User page
     */
    public function indexAction()
    {
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $this->_view = new ViewModel();
        
        $user = $this->getAppUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        $role = $user->getUserProfile();
        
        if ($role != null && $role->getProfile_picture_url() != null) {
            $dataGravatar = array(
                'useGravatar' => false,
                'imgURL' => $role->getProfile_picture_url()
            );
            $this->_view->setVariable('dataGravatar', $dataGravatar);
        }
        
        return $this->_view;
    }

    /**
     * Register new user instead of zfcuser registration
     */
    public function registerAction()
    {
        
        // if the user is logged in, we don't need to register
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()
                ->getLoginRedirectRoute());
        }
        // if registration is disabled
        if (! $this->getOptions()->getEnableRegistration()) {
            return array(
                'enableRegistration' => false
            );
        }
        
        $request = $this->getRequest();
        $service = $this->getUserService();
        $form = $this->getRegisterForm();
        
        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }
        
        $redirectUrl = $this->url()->fromRoute(static::ROUTE_REGISTER) . ($redirect ? '?redirect=' . rawurlencode($redirect) : '');
        $prg = $this->prg($redirectUrl, true);
        
        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect
            );
        }
        
        $post = $prg;
        $user = $service->register($post);
        
        
        $redirect = isset($prg['redirect']) ? $prg['redirect'] : null;
        
        if (! $user) {
            return array(
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect
            );
        }
        
        $this->getRoleService()->setAsUser($user);
        
        if ($service->getOptions()->getLoginAfterRegistration()) {
            $identityFields = $service->getOptions()->getAuthIdentityFields();
            if (in_array('email', $identityFields)) {
                $post['identity'] = $user->getEmail();
            } elseif (in_array('username', $identityFields)) {
                $post['identity'] = $user->getUsername();
            }
            $post['credential'] = $post['password'];
            // $request->getQuery()->set('redirect', 'addprofile');
            $request->getQuery()->set('redirect', 'choose');
            $request->setPost(new Parameters($post));
            return $this->forward()->dispatch(static::CONTROLLER_NAME, array(
                'action' => 'authenticate'
            ));
        }
        
        // TODO: Add the redirect parameter here...
        return $this->redirect()->toUrl($this->url()
            ->fromRoute(static::ROUTE_LOGIN) . ($redirect ? '?redirect=' . rawurlencode($redirect) : ''));
    }

    public function vocalistAction()
    {
        $user = $this->getAppUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        $this->getAppUserService()->setUser($user);
        
        $query = 'SELECT pt FROM '.$this->roleEntity.' pt WHERE pt.role_key = :key';
        $roles = $this->getRoleService()->query($query, array('key' => 'PVo'));

        if (! $user->hasRole($roles[0])) {

            $query = 'SELECT pt FROM '.$this->roleEntity.' pt WHERE pt.role_key = :key';
            $basic = $this->getRoleService()->query($query, array('key' => 'B'));
            
            foreach ($basic as $role) {
                $this->getAppUserService()->removeRole($role);
            }
            
            $this->getAppUserService()->addRole($roles[0]);
        }
        
        if ($user->getUserProfile() == null) {
            return $this->redirect()->toRoute('profile', array(
                'action' => 'add'
            ));
        }
        
        return $this->redirect()->toRoute('profile', array(
            'action' => 'edit'
        ));
    }

    public function lyricistAction()
    {
        $user = $this->getAppUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        $this->getAppUserService()->setUser($user);

        $query = 'SELECT pt FROM '.$this->roleEntity.' pt WHERE pt.role_key = :key';
        $roles = $this->getRoleService()->query($query, array('key' => 'PLy'));
        
        if (! $user->hasRole($roles[0])) {

            $query = 'SELECT pt FROM '.$this->roleEntity.' pt WHERE pt.role_key = :key';
            $basic = $this->getRoleService()->query($query, array('key' => 'B'));
            
            foreach ($basic as $role) {
                $this->getAppUserService()->removeRole($role);
            }
            
            $this->getAppUserService()->addRole($roles[0]);
        }
        
        if ($user->getUserProfile() == null) {
            return $this->redirect()->toRoute('profile', array(
                'action' => 'add'
            ));
        }
        
        return $this->redirect()->toRoute('profile', array(
            'action' => 'edit'
        ));
        
    }

    public function composerAction()
    {
        $user = $this->getAppUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        $this->getAppUserService()->setUser($user);
        
        $query = 'SELECT pt FROM '.$this->roleEntity.' pt WHERE pt.role_key = :key';
        $roles = $this->getRoleService()->query($query, array('key' => 'PCo'));
        
        if (! $user->hasRole($roles[0])) {

            $query = 'SELECT pt FROM '.$this->roleEntity.' pt WHERE pt.role_key = :key';
            $basic = $this->getRoleService()->query($query, array('key' => 'B'));
            
            foreach ($basic as $role) {
                $this->getAppUserService()->removeRole($role);
            }
            
            $this->getAppUserService()->addRole($roles[0]);
        }
        
        if ($user->getUserProfile() == null) {
            return $this->redirect()->toRoute('profile', array(
                'action' => 'add'
            ));
        }
       
        return $this->redirect()->toRoute('profile', array(
            'action' => 'edit'
        ));
    }

    public function basicAction()
    {
        $user = $this->getAppUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        $this->getAppUserService()->setUser($user);
        
        $query = 'SELECT pt FROM '.$this->roleEntity.' pt WHERE pt.role_key = :key';
        $hasRole = $this->getRoleService()->query($query, array('key' => 'B'));
        
        if (! $user->hasRole($hasRole[0])) {
            $basic = $hasRole[0];
            
            //$query = 'SELECT pt FROM '.$this->roleEntity.' pt WHERE pt.role_key = :key';
            //$roles = $this->getRoleService()->query($query, array('key' => 'B'));
            $roles = $this->getAppUserService()->getRoles($user);
            
            $config = $this->getServiceLocator()->get('config');
            
            foreach ($roles as $role) {
                if($role->getId() != $config['MusicLackey']['userRoleId'] && $role->getId()!= $config['MusicLackey']['adminRoleId'])
                    $this->getAppUserService()->removeRole($role);
            }
            
            $this->getAppUserService()->addRole($basic);
        }
        
        if ($user->getUserProfile() == null) {
            return $this->redirect()->toRoute('profile', array('action' => 'add'));
        }
        
        $session = new Container('Users');
        $url = $session->redirect;
        if ($url == "profile/edit") {
            // $this->getServiceLocator()->get('Zend\Log')->info($urlFull[0],$urlFull[1]);
            // return $this->redirect()->toRoute($urlFull[0],$urlFull[1]);
            return $this->redirect()->toRoute('profile', array('action' => 'edit'));
        }
        
        return $this->redirect()->toRoute('profile', array('action' => 'edit'));
        // return $this->redirect()->toRoute($this->replaceUrl($url));
        
        // return $this->redirect()->toRoute('zfcuser/home');
    }

    public function chooseroleAction()
    {
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $headers = $this->getRequest()->getHeaders();
        if ($headers->has('Referer')) {
            $session = new Container('Users');
            $session->redirect = $this->getRequest()
                ->getHeader('Referer')
                ->uri()
                ->getPath();
            $this->getServiceLocator()
                ->get('Zend\Log')
                ->info('choose' . $session->redirect);
        } else {
            return $this->redirect()->toRoute('zfcuser/home');
        }
        
        return new ViewModel();
    }

    public function deleteroleAction()
    {
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $user = $this->getAppUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        $this->getAppUserService()->setUser($user);
        $roles = $this->getAppUserService()->getRoles($user);
        
        $form = new RoleForm('role', $this->getRoleService()->buildRoleSelectbox($roles));
        $form->setAttribute('method', 'post');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $form->setData($request->getPost());
            if ($form->isValid()) {
                
                $data = array_merge_recursive($this->getRequest()->getPost()->toArray());
                
                $key = $data['roles'];
                
                $query = 'SELECT pt FROM '.$this->roleEntity.' pt WHERE pt.role_key = :key';
                $profType = $this->getRoleService()->query($query, array(
                    'key' => $key
                ));
                
                $this->getAppUserService()->removeRole($profType[0]);
                
                return $this->redirect()->toRoute('profile', array(
                    'action' => 'edit'
                ));
            }
        }
        
        return array(
            'id' => $user->getId(),
            'form' => $form,
            'roles' => $user->getRoles()->toArray()
        );
    }
   
    public function testAction()
    {
        return new ViewModel();
    }

    private function replaceUrl($string)
    {
        $this->getServiceLocator()->get('Zend\Log')->info($string);
        if (strpos($string, 'zfcuser') !== FALSE) {
            $change = '/user';
            $url = 'zfcuser';
            return str_replace($change, $url, $string);
        } elseif (strpos($string, 'profile') !== FALSE) {
            echo gettype($string);
            $strs = explode('/', $string);
            // $arr = array_diff($strs, array("/"));
            $action = array(
                $strs[1],
                array(
                    'action' => $strs[2]
                )
            );
            return $action;
        } else
            return $string;
    }

    private function getAppUserService()
    {
        if (! $this->appUserService) {
            $this->appUserService = $this->getServiceLocator()->get('Application\Service\UserService');
        }
        
        return $this->appUserService;
    }

    private function getRoleService()
    {
        if (! $this->roleService) {
            $this->roleService = $this->getServiceLocator()->get('Application\Service\RoleService');
        }
        
        return $this->roleService;
    }
}