<?php
namespace Workspace\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\SongsVersionHistory;
use Application\Entity\Songs;
use Zend\File\Transfer\Adapter\Http;
use Zend\Session\Container;

use Workspace\Form\SongForm;
use Workspace\Form\SongFilter;
use Workspace\Form\UploadFilter;
use Workspace\Form\UploadForm;
use Workspace\Form\LyricsFilter;
use Workspace\Form\LyricsForm;
use Workspace\Form\DeleteForm;
use Workspace\Form\CollaborationForm;
use Workspace\Form\CompleteForm;

class SongController extends AbstractActionController
{

    protected $songService;
    protected $songVersionHistoryService;
    protected $userService;
    protected $mp3Service;

    public function indexAction()
    {
        
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $this->_view = new ViewModel();
        
        $logId = $this->zfcUserAuthentication()->getIdentity()->getId();
        $user = $this->getUserService()->find($logId);

        $this->getUserService()->setUser($user);
        
        $sampleSongs =  $this->getUserService()->getSampleSongs();
        $projects= $this->getUserService()->getProjects();
        $finishedProjects= $this->getUserService()->getFinishedProjects();

        $this->_view->setVariable('samples',$sampleSongs);
        $this->_view->setVariable('projects', $projects);
        $this->_view->setVariable('finishedProjects', $finishedProjects);
        return $this->_view;
    }
    
    public function viewAction()
    {
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $this->_view = new ViewModel();

        $id = $this->params()->fromRoute('id');
        
        $oid = $this->params()->fromRoute('oid');
        
        $song = $this->getSongService()->find($id);

        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        
        if($song->getUsers()->contains($user)){
            $this->_view->setVariable('author', TRUE);
        }
        
        $config = $this->getServiceLocator()->get('config');
        $this->_view->setVariable('playerTime',$config['MusicLackey']['minPlayerTime']);
        
        $this->_view->setVariable('song', $song);
        if($oid != null){
        	$version = $this->getSongVersionHistoryService()->find($oid);
            $this->_view->setVariable('version', $version);
        }
            
        
        return $this->_view;
        
    }
    
    public function editAction(){
        
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
               
        $id = $this->params()->fromRoute('id');
         
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        $song = $this->getSongService()->find($id);
        //$this->_view->setVariable('song', $song);
        
        if($song->getCompleted() == 1){
        	//echo 'song marked as complete';
        	return $this->redirect()->toRoute('song',array('action'=>'view','id'=>$song->getId()));
        }
        
        $form = new SongForm('Song', $this->getServiceLocator());
        
        $form->setBindOnValidate(false);
        $form->bind($song);
       
        $request = $this->getRequest();
        if ($request->isPost()) {
        
        	$form->setInputFilter(new SongFilter($this->getServiceLocator()));
        	$form->setData($request->getPost());
        	
        	if ($form->isValid()) {
        	    $form->bindValues();
        	    
        	    $data = array_merge_recursive($this->getRequest()->getPost()->toArray());

                $song = $this->getSongService()->edit($data,$form->getData()); 		
        		return $this->redirect()->toRoute('song',array('action'=>'complete','id'=>$song->getId()));
        	}
        }
        return array(
        		'form' => $form,
                'song' => $song
        );
    }
    
    public function deleteAction(){
        
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }

        $id = $this->params()->fromRoute('id');
        
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());        
        $song = $this->getSongService()->find($id);
        
        if(!$song->getUsers()->contains($user)){
        	return $this->redirect()->toRoute('workspace', array('action' => 'workspace'));
        }
        
        $form = new DeleteForm('Delete Song');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
        	 
        	//$form->setInputFilter(new SongFilter($this->getServiceLocator()));
        	$form->setData($request->getPost());
        	 
        	if ($form->isValid()) {
        		$this->getSongService()->delete($song);
        
        		return $this->redirect()->toRoute('workspace',array('action'=>'workspace'));
        	}
        }
        return array(
        	'form' => $form,
            'song' => $song
        );
    }
    
    public function addAction(){
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
         
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
         
        $name = $this->params()->fromRoute('name');
        $sample = $this->params()->fromRoute('type');

        //$this->getServiceLocator()->get('Zend\Log')->info($sample.' '.$name);
        
        if($sample != null ){
            $sample = true;
        }else{
        	$sample = false;
        	
        }
        
        $form = new SongForm('Song', $this->getServiceLocator(),$sample,$name);
        
        $request = $this->getRequest();
        if ($request->isPost()) {       
        	
            $form->setInputFilter(new SongFilter($this->getServiceLocator()));
        	$form->setData($request->getPost());
        	
        	if ($form->isValid()) {  
        	   $data = $form->getData();
        	    
        	   //$this->getServiceLocator()->get('Zend\Log')->info($form->getData());
        	   $song = $this->getSongService()->create($data,$user,$sample);
        		
        	   $session = new Container('Songs');
        	   $session->song = $song;
        	   $session->sample = $sample;
        	   
        	   if(isset($data['sampletype'])){
        	       if($data['sampletype'] == 'lyrics')
        	           return $this->redirect()->toRoute('song',array('action'=>'lyrics'));
        	   }
        	   
        	   return $this->redirect()->toRoute('song',array('action'=>'upload'));
        	}
        }
        return array(
        		'form' => $form
        );
    }
    
    public function completeAction(){
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $id = $this->params()->fromRoute('id');
         
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        $song = $this->getSongService()->find($id);
        //$song = $this->getSongService()->find(43);
        
        $form = new CompleteForm('complete', $this->getServiceLocator());
        $request = $this->getRequest();
        if ($request->isPost()) {
        	$form->setData($request->getPost());
        	 
        	if ($form->isValid()) {
        		$data = $form->getData();
        		//echo '<br><br><br>';
        		//var_dump($data);
        		
        		if($data['submit'] != null){
        			$this->getSongService()->setComplete($song);
        		}
        
        		//$song = $this->getSongService()->edit($data,$form->getData());
        		return $this->redirect()->toRoute('workspace',array('action'=>'workspace'));
        	}
        }
        return array(
        		'form' => $form
        );
        
    }
  
    public function uploadAction()
    {
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    	$tempFile = null;
    	$errors = null;
    	
    	$id= $this->zfcUserAuthentication()->getIdentity()->getId();
    	$user = $this->getUserService()->findAndSetUser($id);
    	
    	$session = new Container('Songs');
    	$song = $session->song;
    	$sample = $session->sample;

    	if($sample == 1){
    	   $url = '/samples/';
    	   $filename = 'sample';
    	}else{
            $url = '/works/';
            $filename = 'file';
    	}
    	$form = new UploadForm('Song', $this->getServiceLocator(),false,true);
    
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    	
    	    $form->setInputFilter(new UploadFilter($this->getServiceLocator()));
    		$data = array_merge_recursive($this->getRequest()->getPost()->toArray(), 
    		    $this->getRequest()->getFiles()->toArray());
    		$form->setData($data);
    
    		if ($form->isValid()) {
    		    $data = $form->getData();
    		    
    		    $file = $data['file'];

    		    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    		    $target = './public/uploads'.$url.'/';
    		    $filter = new \Zend\Filter\File\Rename(array(
    		    		'target' => $target.$filename.'.'.$extension,
    		    		'randomize' => true,
    		    		'overwrite' => false
    		    ));
    		    
    		    $upload = $filter->filter($data['file']);
                
    			if ($upload['error'] == 0) {
    			    $lenght = strlen($target);
    			    $name = substr($upload['tmp_name'], $lenght-1);
    			    
    			    $this->getMp3Service()->initialize($target.$name);
    			    $info = $this->getMp3Service()->getMetadata();
    			    
    			    $config = $this->getServiceLocator()->get('config');

    			    echo '<br><br><br><br>';
    			    echo $config['MusicLackey']['comedy'];
    			    echo '<br>';
    			    echo $song->getGenre()->getId();
    			    echo '<br>';
    			    echo $info['Length'];
    			    echo '<br>';
    			    echo $config['MusicLackey']['minComedyLength'];
    			    echo '<br>';
    			    echo $config['MusicLackey']['minLength'];
    			    
    			    
    			    if(($song->getGenre()->getId() == $config['MusicLackey']['comedy'] &&
    			    		$info['Length'] >= $config['MusicLackey']['minComedyLength']) ||
    			    		($song->getGenre()->getId() != $config['MusicLackey']['comedy'] && 
    			    		    $info['Length'] >= $config['MusicLackey']['minLength'])){
    			    		
    			    	$version = $this->getSongVersionHistoryService()->create($data,$id,$song->getId(),$name,$url,
    				    $info['Bitrate'],$info['Length hh:mm:ss']);
    			    
    			    	//echo '<br><br><br><br>entered here omg 1';
    			    	return $this->redirect()->toRoute('song',array('action'=>'complete','id'=>$song->getId()));
    			    		
    			    }else{
    			    	$errors = 'SONG DOES NOT MEET MINIMUM LENGTH';
    			    }

    			} else {
    				$errors = $upload['error'];
    			}
    			
    		} else {
    			// Form not valid, but file uploads might be valid...
    			// Get the temporary file information to show the user in the view
    		    $fileErrors = $form->get('file')->getMessages();
    			if (empty($fileErrors)) {
    				$tempFile = $form->get('file')->getValue();
    			}
    		}
    	}
    	return array(
    			'form' => $form,
    			'tempFile' => $tempFile,
    	       'errors'=>$errors,
    	       'song' => $song
    	);
    }
    
    public function lyricsAction()
    {
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}

    	$id= $this->zfcUserAuthentication()->getIdentity()->getId();
    	//$user = $this->getUserService()->findAndSetUser($id);
    	
    	$session = new Container('Songs');
    	$song = $session->song;
    	$sample = $session->sample;

   
    	$form = new LyricsForm('Lyrics', $this->getServiceLocator());
    	
    
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    	    
    	    $form->setInputFilter(new LyricsFilter($this->getServiceLocator()));
    		$form->setData($request->getPost());
    
    		if ($form->isValid()) {
    		    $data = $form->getData();
    			$version = $this->getSongVersionHistoryService()->create($data,$id,$song->getId());

    			return $this->redirect()->toRoute('workspace',array('action'=>'workspace'));
    		}
    	}
    	return array(
    		'form' => $form,
    	    'song' => $song
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
    
    private function getSongService()
    {
    	if (!$this->songService) {
    		$this->songService = $this->getServiceLocator()->get('Application\Service\SongService');
    	}
    
    	return $this->songService;
    }
    
    private function getSongVersionHistoryService()
    {
    	if (!$this->songVersionHistoryService) {
    		$this->songVersionHistoryService = $this->getServiceLocator()->get('Application\Service\SongVersionHistoryService');
    	}
    
    	return $this->songVersionHistoryService;
    }

    private function getUserService()
    {
    	if (!$this->userService) {
    		$this->userService = $this->getServiceLocator()->get('Application\Service\UserService');
    	}
    
    	return $this->userService;
    }
    
    private function getMp3Service()
    {
    	if (!$this->mp3Service) {
    		$this->mp3Service = $this->getServiceLocator()->get('Application\Service\Mp3Service');
    	}
    
    	return $this->mp3Service;
    }
    
    private function buildSelectbox($fields)
    {
    	$select = array();
    	foreach ($fields as $field) {
    		$select[$field->getId()] = $field->getUsername();
    	}
    
    	return $select;
    }
}