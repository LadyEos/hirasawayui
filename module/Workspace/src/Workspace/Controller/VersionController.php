<?php
namespace Workspace\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Application\Entity\Users;
use Workspace\Form\PastForm;
use Workspace\Form\UploadForm;
use Workspace\Form\UploadFilter;
use Application\Entity\SongsVersionHistory;
use Application\Entity\Songs;
use Zend\File\Transfer\Adapter\Http;
use Zend\Session\Container;

class VersionController extends AbstractActionController
{

    protected $userService;
    protected $songService;
    protected $songVersionHistoryService;
    protected $mp3Service;
    protected $genreService;

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
        
        $roles= $user->getRoles();
        
        if($roles->first()->getRole_key() =='B')
        	return $this->redirect()->toRoute('zfcuser/home');
        
        $audioFlag = false;
        $lyricsFlag = false;
        $tempFile = '';
        $errors = '';
        
        foreach ($roles as $role){
        	if($role->getRole_key() == 'PLy')
        	    $lyricsFlag = true;
        	if($role->getRole_key() == 'PCo' || $role->getRole_key() == 'PVo')
        	    $audioFlag = true;
        }

        //$song = $this->getSongService()->find($songId);
        $song = $this->getSongService()->find(46);
        $sample = $song->getSample();
    	
        if($sample == 1){
    	   $url = '/samples/';
    	   $filename = 'sample';
    	}else{
            $url = '/works/';
            $filename = 'file';
    	}
    	$form = new UploadForm('Version', $this->getServiceLocator(),$lyricsFlag,$audioFlag);

    	$request = $this->getRequest();
    	if ($request->isPost()) {
    	
    	    $form->setInputFilter(new UploadFilter($this->getServiceLocator()));
    		$data = array_merge_recursive($this->getRequest()->getPost()->toArray(), 
    		    $this->getRequest()->getFiles()->toArray());
    		$form->setData($data);
    
    		if ($form->isValid()) {
                
    		    $data = $form->getData();
    		    
    		    if($lyricsFlag && !$audioFlag){
    		        $version = $this->getSongVersionHistoryService()->create($data,$id,$song->getId());
    		        //echo '<br><br><br><br>entered here omg';
    		        return $this->redirect()->toRoute('workspace',array('action'=>'workspace'));
    		    }else{

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
        			    
        			    if(($song->getGenre()->getId() == $config['MusicLackey']['comedy'] && 
        			        $info['Length'] >= $config['MusicLackey']['minComedyLength']) || 
        			        ($song->getGenre()->getId() != $config['MusicLackey']['comedy'] && 
        			            $info['Length'] >= $config['MusicLackey']['minLength'])){
        			        
        			        $version = $this->getSongVersionHistoryService()->create($data,$id,$song->getId(),$name,$url
        			        		,$info['Bitrate'],$info['Length hh:mm:ss']);
        			         
        			        //echo '<br><br><br><br>entered here omg 1';
        			        return $this->redirect()->toRoute('workspace',array('action'=>'workspace'));
        			        
        			    }else{
        			        $errors = 'SONG DOES NOT MEET MINIMUM LENGTH';
        			    }
        			    
        			    
        			} else if($upload['error'] == 4){//no file
        			    $version = $this->getSongVersionHistoryService()->create($data,$id,$song->getId());
        			    //echo '<br><br><br><br>entered here omg 2';
        			    return $this->redirect()->toRoute('workspace',array('action'=>'workspace'));
        			}else {
        				$errors = $upload['error'];
        			}
    			
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
    
    private function getMp3Service()
    {
    	if (!$this->mp3Service) {
    		$this->mp3Service = $this->getServiceLocator()->get('Application\Service\Mp3Service');
    	}
    
    	return $this->mp3Service;
    }
    
    private function getGenreService()
    {
    	if (!$this->genreService) {
    		$this->genreService = $this->getServiceLocator()->get('Application\Service\GenreService');
    	}
    
    	return $this->genreService;
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