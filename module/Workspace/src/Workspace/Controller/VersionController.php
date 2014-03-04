<?php
namespace Workspace\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Application\Entity\Users;
use Workspace\Form\PastForm;
use Workspace\Form\VersionForm;
use Workspace\Form\VersionFilter;
use Application\Entity\SongsVersionHistory;
use Application\Entity\Songs;
use Zend\File\Transfer\Adapter\Http;
use Zend\Session\Container;

class VersionController extends AbstractActionController
{

    protected $userService;
    protected $songService;
    protected $songVersionHistoryService;

    public function indexAction()
    {
        return new ViewModel();
    }

    public function addAction()
    {
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        $id =$this->zfcUserAuthentication()->getIdentity()->getId();
        $user = $this->getUserService()->findAndSetUser($id);
        
        $songId = $this->params()->fromRoute('id');
        
        $profTypes= $user->getProfile_types();
        
        if($profTypes->first()->getProfile_key() =='B')
        	return $this->redirect()->toRoute('zfcuser/home');
        
        $audioFlag = false;
        $lyricsFlag = false;
        $tempFile = null;
        $errors = null;
        
        foreach ($profTypes as $profile){
        	if($profile->getProfile_key() == 'PLy')
        	    $lyricsFlag = true;
        	if($profile->getProfile_key() == 'PCo' || $profile->getProfile_key() == 'PVo')
        	    $audioFlag = true;
        }

        $song = $this->getSongService()->find($songId);
        $sample = $song->getSample();
    	
        if($sample == 1){
    	   $url = '/samples/';
    	   $filename = 'sample';
    	}else{
            $url = '/works/';
            $filename = 'file';
    	}
    	$form = new VersionForm('Version', $this->getServiceLocator(),$lyricsFlag,$audioFlag);
    	
    	
    
    	$upload = new \Zend\File\Transfer\Transfer();
    	$upload->setDestination('./public/uploads'.$url);
    	$upload->addFilter('File\Rename',array(
    			'target' => './public/uploads'.$url.$filename.'.mp3',
    			'randomize' => true,
    			'use_upload_extension' => true
    	));
    
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    	
    	    $form->setInputFilter(new VersionFilter($this->getServiceLocator()));
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
    				$version = $this->getSongVersionHistoryService()->create($data,$id,$song->getId(),null,null);
    					
    				return $this->redirect()->toRoute('workspace',array('action'=>'workspace'));
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
    
    public function pastAction()
    {
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        $id =$this->zfcUserAuthentication()->getIdentity()->getId();
        $user = $this->getUserService()->findAndSetUser($id);
        
        $songId = $this->params()->fromRoute('id');
        $song = $this->getSongService()->find($songId);
        
        if(!$song->getUsers()->contains($user)){
            return $this->redirect()->toRoute('song', array('action' => 'view','id'=>$songId));
        }
        
        if($song->getVersions()->count() == 1){
            return $this->redirect()->toRoute('song', array('action' => 'view','id'=>$songId));
        }
        
        $form = new PastForm('version', $this->buildSelectbox($song->getVersions()));
        $form->setAttribute('method', 'post');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
        
        	$form->setData($request->getPost());
        	if ($form->isValid()) {
        
        		$data = array_merge_recursive($this->getRequest()->getPost()->toArray());
        
        		$key = $data['version'];
        
        		
        		return $this->redirect()->toRoute('song', array('action' => 'view','id'=>$songId,'oid'=>$data['version']));
        	}
        }
        
        return array(
        		'id' => $id,
        		'form' => $form,
        		'song' => $song
        );
    }
    
    private function getUserService()
    {
    	if (!$this->userService) {
    		$this->userService = $this->getServiceLocator()->get('Application\Service\UserService');
    	}
    
    	return $this->userService;
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
    
    private function buildSelectbox($versions)
    {
    	$select = array();
    	foreach ($versions as $version) {
    		$select[$version->getId()] = $version->getVersion().' - '.$version->getCreated()->format('Y-m-d');
    	}
    
    	return $select;
    }
}