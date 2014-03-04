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

class SongController extends AbstractActionController
{

    protected $songService;
    protected $songVersionHistoryService;
    protected $userService;

    public function indexAction()
    {
        return new ViewModel();
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
        
        	$form->setInputFilter(new UploadFilter($this->getServiceLocator()));
        
        	if ($form->isValid()) {
                $song = $this->getSongService()->create($data,$user,$sample,$file,$url); 		
        		return $this->redirect()->toRoute('workspace',array('action'=>'workspace'));
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
        
        $this->_view = new ViewModel();
        
        $id = $this->params()->fromRoute('id');
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
        	    
        	   $this->getServiceLocator()->get('Zend\Log')->info($form->getData());
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

    	//$song = $this->getSongService()->find($songId);
    	
    	if($sample == 1){
    	   $url = '/samples/';
    	   $filename = 'sample';
    	   //$form = new UploadForm('Sample', $this->getServiceLocator(),$sample);
    	}else{
            $url = '/works/';
            $filename = 'file';
            //$form = new UploadForm('Upload', $this->getServiceLocator(),$sample);
    	}
    	$form = new UploadForm('Sample', $this->getServiceLocator(),$sample);
    
    	$upload = new \Zend\File\Transfer\Transfer();
    	$upload->setDestination('./public/uploads'.$url);
    	$upload->addFilter('File\Rename',array(
    			'target' => './public/uploads'.$url.$filename.'.mp3',
    			'randomize' => true,
    			'use_upload_extension' => true
    	));
    
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    	
    	    $form->setInputFilter(new UploadFilter($this->getServiceLocator()));
    		$data = array_merge_recursive($this->getRequest()->getPost()->toArray(), 
    		    $this->getRequest()->getFiles()->toArray());
    		$form->setData($data);
    
    		if ($form->isValid()) {
    
    			if ($upload->receive()) {
    			    
    				$file = $upload->getFileInfo();
    				
    				$version = $this->getSongVersionHistoryService()->create($data,$id,$song->getId(),$file,$url);
    				
    				return $this->redirect()->toRoute('workspace',array('action'=>'workspace'));
    			} else {
    				$errors = $upload->getErrors();
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
}