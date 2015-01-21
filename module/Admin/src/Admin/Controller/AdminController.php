<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Users\Form\RoleForm;
use Admin\Form\PriceForm;
use Admin\Form\PriceFilter;
use Admin\Form\NewRoleForm;
use Admin\Form\RoleFilter;

class AdminController extends AbstractActionController
{
    protected $songService;
    protected $userService;
    protected $roleService;
    protected $roleEntity = 'Application\Entity\Role';
    protected $priceService;
   
    public function indexAction()
    {
        if (!$this->isAllowed('admin', 'view')) {
        	throw new \BjyAuthorize\Exception\UnAuthorizedException('Grow a beard first!');
        }
        return new ViewModel();
    }
    
    public function usersAction()
    {
        /*if (!$this->isAllowed('admin', 'view')) {
        	throw new \BjyAuthorize\Exception\UnAuthorizedException('Grow a beard first!');
        }*/
        
        $this->_view = new ViewModel();
        
        $users = $this->getUserService()->findAll();
        //$this->_view->setVariable('users', );
    	
        $paginator = new Paginator(new ArrayAdapter($users));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));//$this->params()->fromRoute('page')
        //$paginator->setCurrentPageNumber($page);
        //$paginator->setItemCountPerPage(10);
        $config = $this->getServiceLocator()->get('config');
        $paginator->setItemCountPerPage($config['MusicLackey']['paginator']['pages']);
        
        $this->_view->setVariable('paginator',$paginator);
        
        
        return $this->_view;
    }
    
    public function roleAction(){
        
        $id = $this->params()->fromRoute('id');
        $action = $this->params()->fromRoute('act');
        
        $user = $this->getUserService()->find($id);
        
        if($action == 'add')
            $roles = $this->getRoleService()->findAll();
        else
            $roles = $user->getRoles()->toArray();
        
        $form = new RoleForm('Role', $this->getRoleService()->buildRoleSelectbox($roles));
        
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
                
                $this->getUserService()->setuser($user);
                
                if($action == 'add')
                    $this->getUserService()->addRole($profType[0]);
                else
                    $this->getUserService()->removeRole($profType[0]);
                
                return $this->redirect()->toRoute('zfcadmin/users');
            }
        }
        return array(
            'action' => $action,
            'form' => $form,
            //'roles' => $user->getRoles()->toArray()
        );
    }

    public function songsAction(){
        $this->_view = new ViewModel();
        
        $songs = $this->getSongService()->findBy(array('completed'=>1,'sample'=>0));
        //$this->_view->setVariable('users', );
         
        $paginator = new Paginator(new ArrayAdapter($songs));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));//$this->params()->fromRoute('page')
        //$paginator->setCurrentPageNumber($page);
        //$paginator->setItemCountPerPage(10);
        $config = $this->getServiceLocator()->get('config');
        $paginator->setItemCountPerPage($config['MusicLackey']['paginator']['pages']);
        
        $this->_view->setVariable('paginator',$paginator);
        
        
        return $this->_view;
    }
    
    public function priceAction(){
    
        /*$validator = new \Zend\I18n\Validator\Float();
        $messages  = $validator->getMessageTemplates();
        var_dump($messages);*/
        
        $id = $this->params()->fromRoute('id');
        $song = $this->getSongService()->find($id);
    
    
    	$form = new PriceForm('price');
    
    	$form->setAttribute('method', 'post');
    	 
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    	    $form->setInputFilter(new PriceFilter());
    		$form->setData($request->getPost());
    		if ($form->isValid()) {
    
    			$data = $form->getData();
                $price = $this->getPriceService()->create($data['amount'],$song);
    			
    			return $this->redirect()->toRoute('zfcadmin/songs');
    		}
    	}
    	return array(
    			'form' => $form,
    			//'roles' => $user->getRoles()->toArray()
    	);
    }
    
    public function newroleAction(){
        
        $roles = $this->getRoleService()->findAll();
        //echo '<br><br><br><br><br>';
        //var_dump($roles);
        $form = new NewRoleForm('role', $this->getRoleService()->buildRoleSelectbox($roles));
        $form->setAttribute('method', 'post');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter(new RoleFilter());
            $form->setData($request->getPost());
        	if ($form->isValid()) {
        
        		$data = array_merge_recursive($this->getRequest()->getPost()->toArray());
        
        		$key = $data['parentroles'];
                
        		if($key != null){
            		$query = 'SELECT pt FROM '.$this->roleEntity.' pt WHERE pt.role_key = :key';
            		$profType = $this->getRoleService()->query($query, array(
            				'key' => $key
            		));
            
            		$this->getRoleService()->create($data['role'], $data['rolekey'], $data['height'],$profType[0]);
        		}else{
        		    $this->getRoleService()->create($data['role'], $data['rolekey'], $data['height']);
        		}
        		
        		return $this->redirect()->toRoute('zfcadmin/users');
        	}
        }
        return array(
        		//'action' => $action,
        		'form' => $form,
        		//'roles' => $user->getRoles()->toArray()
        );
    }
    
    private function getSongService()
    {
    	if (!$this->songService) {
    		$this->songService = $this->getServiceLocator()->get('Application\Service\SongService');
    	}
    
    	return $this->songService;
    }
    
    private function getPriceService()
    {
    	if (!$this->priceService) {
    		$this->priceService = $this->getServiceLocator()->get('Application\Service\PriceService');
    	}
    
    	return $this->priceService;
    }
    
    private function getUserService()
    {
    	if (!$this->userService) {
    		$this->userService = $this->getServiceLocator()->get('Application\Service\UserService');
    	}
    
    	return $this->userService;
    }
    
    private function getRoleService()
    {
    	if (! $this->roleService) {
    		$this->roleService = $this->getServiceLocator()->get('Application\Service\RoleService');
    	}
    
    	return $this->roleService;
    }
}