<?php
namespace Avatar\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Application\Entity\Users;
use Application\Entity\UserProfiles;
use Avatar\Form\UploadFilter;
use Avatar\Form\UploadForm;
use Zend\File\Transfer\Adapter\Http;
use Zend\Console\Request;

class AvatarController extends AbstractActionController
{

    protected $flag = false;
    protected $uriPaths = array();
    protected $userService;
    protected $profileService;
    protected $albumService;
    protected $songService;
    
    public function indexAction()
    {
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        return new ViewModel();
    }

    public function uploadAction()
    {
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $url = '/images/tmpuploads/';
        
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        
        if ($user->getUserProfile() == null) {
        	return $this->redirect()->toRoute('zfcuser/addprofile');
        }
        
        $profile = $user->getUserProfile();
        $this->getProfileService()->setProfile($profile);

        $form = new UploadForm('upload-form');

        if ($this->getRequest()->isPost()) {
            // Postback
            $form->setInputFilter(new UploadFilter($this->getServiceLocator()));
            $data = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
            	$this->getRequest()->getFiles()->toArray());
            $form->setData($data);
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                //$upload = new \Zend\File\Transfer\Transfer();
                //$upload->setDestination('./public'.$url);
                
                //$file = $upload->getFileInfo();
                $extension = pathinfo($data['file']['name'], PATHINFO_EXTENSION);
                	
                //$uniqueId = uniqid();
                $string = './public'.$url.'/';
                $filter = new \Zend\Filter\File\Rename(array(
                		'target' => $string.'avatar.'.$extension,
                		'randomize' => true,
                		'overwrite' => false
                ));
                
                //$upload->addFilter($filter);
                /*$upload->addFilter('File\Rename',array(
                        'target' => 'avatar.'.$extension,
                		'randomize' => true,
                		'overwrite' => false
                ));*/
                
                //$filters = $upload->getFilters();
                //$filter = $upload->getFilter('Zend\Filter\File\Rename');
                
                //echo '<br><br><br><br><br>';
                $upload = $filter->filter($data['file']);
                //var_dump($upload);
                //var_dump($data);
                //$file = $upload->getFileInfo();
                
                //var_dump($filter->getFile());
                
                //var_dump($filter);
                //var_dump($upload->getFileName());

                if ($upload['error'] == 0) {
                    $lenght = strlen($string);
                    $name = substr($upload['tmp_name'], $lenght-1);
                    
                    $this->getProfileService()->setProfilePicture($url.$name);
                } else {
                    $errors = $upload['error'];
                }
                
                
                //return $this->redirect()->toRoute('zfcuser/home');
            }
        }
        
        return array(
            'form' => $form,
        );
    }
    
    
    public function albumcoverAction(){
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $url = '/images/tmpuploads/albumcover/';
        
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        
        $id = $this->params()->fromRoute('id');
        $album = $this->getAlbumService()->find($id);
        
        
        $form = new UploadForm('upload-form');
        
        if ($this->getRequest()->isPost()) {
        	// Postback
        	$form->setInputFilter(new UploadFilter($this->getServiceLocator()));
        	$data = array_merge_recursive(
        			$this->getRequest()->getPost()->toArray(),
        			$this->getRequest()->getFiles()->toArray());
        	$form->setData($data);
        
        	if ($form->isValid()) {
        		$data = $form->getData();
        
        		$extension = pathinfo($data['file']['name'], PATHINFO_EXTENSION);

        		$string = './public'.$url.'/';
        		$filter = new \Zend\Filter\File\Rename(array(
        				'target' => $string.'acover.'.$extension,
        				'randomize' => true,
        				'overwrite' => false
        		));
        
        		$upload = $filter->filter($data['file']);

        		if ($upload['error'] == 0) {
        			$lenght = strlen($string);
        			$name = substr($upload['tmp_name'], $lenght-1);
        
        			$this->getAlbumService()->setAlbumCover($album, $url.$name);
        		} else {
        			$errors = $upload['error'];
        		}
        
        		return $this->redirect()->toRoute('album', array(
    			'action' => 'index'));
        	}
        }
        
        return array(
        		'form' => $form,
        );
    }
    
    public function songcoverAction(){
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    
    	$url = '/images/tmpuploads/songcover/';
    
    	$user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
    
    	$id = $this->params()->fromRoute('id');
    	$song = $this->getSongService()->find($id);
    
    
    	$form = new UploadForm('upload-form');
    
    	if ($this->getRequest()->isPost()) {
    		// Postback
    		$form->setInputFilter(new UploadFilter($this->getServiceLocator()));
    		$data = array_merge_recursive(
    				$this->getRequest()->getPost()->toArray(),
    				$this->getRequest()->getFiles()->toArray());
    		$form->setData($data);
    
    		if ($form->isValid()) {
    			$data = $form->getData();
    
    			$extension = pathinfo($data['file']['name'], PATHINFO_EXTENSION);
    
    			$string = './public'.$url.'/';
    			$filter = new \Zend\Filter\File\Rename(array(
    					'target' => $string.'scover.'.$extension,
    					'randomize' => true,
    					'overwrite' => false
    			));
    
    			$upload = $filter->filter($data['file']);
    
    			if ($upload['error'] == 0) {
    				$lenght = strlen($string);
    				$name = substr($upload['tmp_name'], $lenght-1);
    
    				$this->getSongService()->setSongCover($song, $url.$name);
    			} else {
    				$errors = $upload['error'];
    			}
    
    			return $this->redirect()->toRoute('workspace', array(
    					'action' => 'workspace'));
    		}
    	}
    
    	return array(
    			'form' => $form,
    	);
    }
 
    private function getUserService()
    {
    	if (!$this->userService) {
    		$this->userService = $this->getServiceLocator()->get('Application\Service\UserService');
    	}
    
    	return $this->userService;
    }
    
    private function getProfileService()
    {
    	if (!$this->profileService) {
    		$this->profileService = $this->getServiceLocator()->get('Application\Service\ProfileService');
    	}
    
    	return $this->profileService;
    }
    
    private function getAlbumService()
    {
    	if (!$this->albumService) {
    		$this->albumService = $this->getServiceLocator()->get('Application\Service\AlbumService');
    	}
    
    	return $this->albumService;
    }
    
    private function getSongService()
    {
    	if (!$this->songService) {
    		$this->songService = $this->getServiceLocator()->get('Application\Service\SongService');
    	}
    
    	return $this->songService;
    }
}