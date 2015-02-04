<?php
namespace Workspace\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Songs;
use Application\Entity\Albums;
use Zend\Session\Container;

use Workspace\Form\AlbumForm;
use Workspace\Form\AlbumFilter;
use Workspace\Form\SongForm;
use Workspace\Form\SongFilter;
use Workspace\Form\DeleteForm;

class AlbumController extends AbstractActionController
{

    protected $songService;
    protected $userService;
    protected $albumService;
    
    const AMOUNT    = 0.80;

    public function indexAction()
    {
        
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $this->_view = new ViewModel();
        
        $logId = $this->zfcUserAuthentication()->getIdentity()->getId();
        $user = $this->getUserService()->find($logId);
        
        $albums =  $user->getAlbums();
        //var_dump($albums);
        
        $this->_view->setVariable('albums',$albums);
        return $this->_view;
    }
    
    public function viewAction()
    {
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $this->_view = new ViewModel();
        $album = $this->getAlbumService()->find($this->params()->fromRoute('id'));
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        $authorize = $this->getServiceLocator()->get('BjyAuthorize\Provider\Identity\ProviderInterface');
        $roles = $authorize->getIdentityRoles();
        
        if($album->getUsers()->contains($user)){
            $this->_view->setVariable('author', TRUE);
        }
        
        $config = $this->getServiceLocator()->get('config');
        
        $djId = $config['MusicLackey']['djRoleId'];
        $playerTime = $config['MusicLackey']['minPlayerTime'];
        
        if($this->checkId($roles, $djId)){
        	$playerTime = 0;
        }
        
        $this->_view->setVariable('playerTime',$playerTime);
        $this->_view->setVariable('album', $album);
        $this->_view->setVariable('playlist', $this->getAlbumService()->playlist($album));

        return $this->_view;
        
    }
    
    public function editAction(){
        
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
               
        $user = $this->getUserService()->find($this->zfcUserAuthentication()->getIdentity()->getId());
        $id = $this->params()->fromRoute('id');
        $album = $this->getAlbumService()->find($id);
        
        if($album->getCompleted() == 1){
        	//echo 'song marked as complete';
        	return $this->redirect()->toRoute('album',array('action'=>'view','id'=>$album->getId()));
        }
        
        $selected = $this->getSelectedOptionsForSelect($album);
        $options = $this->getOptionsForSelect($user);
        $form = new AlbumForm('album', $this->getServiceLocator(),$options,$selected);
        $form->setAttribute('method', 'post');
        
        $form->setBindOnValidate(false);
        $form->bind($album);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter(new AlbumFilter($this->getServiceLocator()));
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $form->bindValues();
                $data = $form->getData();
                
                $data = array_merge_recursive($this->getRequest()->getPost()->toArray());
            	$this->getAlbumService()->edit($data, $user,$album,$selected);
            	
            	return $this->redirect()->toRoute('album',array('action'=>'index'));
            	
            }
        	
        }
        return array(
        		'form' => $form,
        );
    }
    
    public function deleteAction(){
        
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }

        $id = $this->params()->fromRoute('id');
        
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());        
        $album = $this->getAlbumService()->find($id);
        
        if(!$album->getUsers()->contains($user)){
        	return $this->redirect()->toRoute('album', array('action' => 'index'));
        }
        
        $form = new DeleteForm('Delete Album');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
        	 
        	//$form->setInputFilter(new SongFilter($this->getServiceLocator()));
        	$form->setData($request->getPost());
        	 
        	if ($form->isValid()) {
        		$this->getAlbumService()->delete($album);
        
        		return $this->redirect()->toRoute('album',array('action'=>'index'));
        	}
        }
        return array(
        	'form' => $form,
            'album' => $album
        );
    }
     
    public function addAction()
    {
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    
    	$user = $this->getUserService()->find($this->zfcUserAuthentication()->getIdentity()->getId());
    
    	$options = $this->getOptionsForSelect($user);
    	$form = new AlbumForm('album', $this->getServiceLocator(),$options);
    	$form->setAttribute('method', 'post');

    	$request = $this->getRequest();
    
    	if ($request->isPost()) {
    	   
    		$form->setInputFilter(new AlbumFilter($this->getServiceLocator()));
    		$form->setData($request->getPost());
    
    		if ($form->isValid()) {
    			$data = $form->getData();
    			
    			$album = $this->getAlbumService()->create($data, $user);
    			
    			return $this->redirect()->toRoute('album',array('action'=>'index'));
    		}
    	}
    	return array(
    			'form' => $form,
    			'error' => 'there was an error'
    	);
    }

    public function collaborationAction(){
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $songId = $this->params()->fromRoute('id');
         
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        $song = $this->getSongService()->find($songId);
        
        $mutuals = $this->getUserService()->getMutuals($user);
        $form = new CollaborationForm('collaboration',$this->buildSelectbox($mutuals));
        
        $request = $this->getRequest();
        if ($request->isPost()) {
        	 
        	$form->setData($request->getPost());
        	 
        	if ($form->isValid()) {
        		$data = $form->getData();
        		 
        		$this->getUserService()->addAuthor($this->getUserService()->find($data['friends']), $song);
        
        		return $this->redirect()->toRoute('song',array('action'=>'edit','id'=>$songId));
        	}
        }
        return array(
        		'form' => $form
        );
        
    }
    
    public function collaborateAction(){
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
         
        $id = $this->params()->fromRoute('id');
        $songId = $this->params()->fromRoute('oid');
         
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        $song = $this->getSongService()->find($songId);
        
        
        $userCol = $this->getUserService()->find($id);
        
        if(!$song->hasUser()){
        	$song->setUser($userCol);
        }
        
        return $this->redirect()->toRoute('song', array(
    			'action' => 'edit',
    			'id'=>$songId
    	));
    }

    public function testAction(){
    	
        $user = $this->getUserService()->find($this->zfcUserAuthentication()->getIdentity()->getId());
        $id = $this->params()->fromRoute('id');
        $album = $this->getAlbumService()->find($id);
        
        $this->getAlbumService()->test($album);
        
        return new ViewModel();
    }
    
    public function getOptionsForSelect($user)
    {
        $this->getUserService()->setUser($user);
    	$result = $this->getUserService()->getFinishedProjects();
    
    	$selectData = array();
    
    	foreach ($result as $res) {
    		$selectData[$res->getId()] = $res->getName();
    	}
    
    	return $selectData;
    }
    
    public function getSelectedOptionsForSelect($album)
    {
    	$result = $album->getSongs();    
    	$selectData = array();
    
    	foreach ($result as $res) {
    		$selectData[] = $res->getId();
    	}
        return $selectData;
    }
    
    private function getSongService()
    {
    	if (!$this->songService) {
    		$this->songService = $this->getServiceLocator()->get('Application\Service\SongService');
    	}
    
    	return $this->songService;
    }

    private function getUserService()
    {
    	if (!$this->userService) {
    		$this->userService = $this->getServiceLocator()->get('Application\Service\UserService');
    	}
    
    	return $this->userService;
    }
    
    private function getAlbumService()
    {
    	if (!$this->albumService) {
    		$this->albumService = $this->getServiceLocator()->get('Application\Service\AlbumService');
    	}
    
    	return $this->albumService;
    }
    
    private function checkId($roles,$id){
    	foreach ($roles as $role){
    		if($role->getId() == $id)
    			return true;
    	}
    	return false;
    }
}