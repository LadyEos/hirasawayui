<?php
namespace Workspace\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Application\Entity\Users;
use Workspace\Form\SampleForm;
use Workspace\Form\SampleFilter;
use Workspace\Form\UploadFilter;
use Workspace\Form\UploadForm;
use Workspace\Form\SampleLyricsForm;
use Workspace\Form\SampleLyricsFilter;
use Application\Entity;
use Application\Entity\SongsVersionHistory;
use Application\Entity\Songs;
use Zend\File\Transfer\Adapter\Http;

class SongController extends AbstractActionController
{

    protected $songService;
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
        
        $song = $this->getSongService()->findSong($id);

        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        
        if($song->getUsers()->contains($user)){
            $this->_view->setVariable('author', TRUE);
        }
        
        $this->_view->setVariable('song', $song);
        
        return $this->_view;
        
    }
    
    public function editAction(){
        
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
               
        $id = $this->params()->fromRoute('id');
    }
    
    public function deleteAction(){
        
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $this->_view = new ViewModel();
        
        $id = $this->params()->fromRoute('id');
    }
  
    public function uploadAction()
    {
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    	$tempFile = null;
    	
    	$user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
    	
    	$name = $this->params()->fromRoute('name');
    	$sample = $this->params()->fromRoute('type');

    	if($sample != null){
    	   $url = '/samples/';
    	   $filename = 'sample';
    	   $form = new SampleForm('Sample', $this->getServiceLocator(),$name);
    	}else{
            $url = '/works/';
            $filename = 'file';
            $form = new UploadForm('Upload', $this->getServiceLocator());
    	}
    	
    
    	$upload = new \Zend\File\Transfer\Transfer();
    	$upload->setDestination('./public/uploads'.$url);
    	$upload->addFilter('File\Rename',array(
    			'target' => './public/uploads'.$url.$filename.'.mp3',
    			'randomize' => true,
    			'use_upload_extension' => true
    	));
    
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		
    	    if($sample != null){
    	    	$form->setInputFilter(new SampleFilter($this->getServiceLocator()));
    	    }else{
    	    	$form->setInputFilter(new UploadFilter($this->getServiceLocator()));
    	    }

    		$data = array_merge_recursive($this->getRequest()->getPost()->toArray(), 
    		    $this->getRequest()->getFiles()->toArray());
    		$form->setData($data);
    
    		if ($form->isValid()) {
    
    			if ($upload->receive()) {
    			    
    				$file = $upload->getFileInfo();
    				
    				$song = $this->getSongService()->saveSong($data,$user,$sample,$file,$url);
    				
    				
    			} else {
    				$errors = $uploadedFile->getErrors();
    			}
    			return $this->redirect()->toRoute('workspace',array('action'=>'workspace'));
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
    	);
    }
    
    public function uploadlyricsAction()
    {
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    
    	$user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
    	 
    	$name ='lyrics';
    	$sample = 'sample';
    	 
    	$form = new SampleLyricsForm('Sample', $this->getServiceLocator());
    
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$form->setInputFilter(new SampleLyricsFilter($this->getServiceLocator()));
    		$form->setData($request->getPost());
    		if ($form->isValid()) {
    			$data = $form->getData();
    			
    			$song = $this->getSongService()->saveSong($data,$user,$sample);
    			
    			return $this->redirect()->toRoute('workspace',array('action'=>'workspace'));
    		}
    	}
    	return array(
    			'form' => $form
    	);
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
}