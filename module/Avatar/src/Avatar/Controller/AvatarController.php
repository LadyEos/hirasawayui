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
        
        //$this->_view = new ViewModel();
        $form = new UploadForm('upload-form');

        $upload = new \Zend\File\Transfer\Transfer();
        $upload->setDestination('./public'.$url);
        $upload->addFilter('File\Rename',array(
        	'target' => './public'.$url.'avatar.png',
            'randomize' => true,
            'use_upload_extension' => true
        ));
        
        if ($this->getRequest()->isPost()) {
            // Postback
            $data = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(), 
                $this->getRequest()->getFiles()->toArray());
            
            $form->setData($data);
            if ($form->isValid()) {

                if ($upload->receive()) {
                    $file = $upload->getFileInfo();
                    $this->getProfileService()->setProfilePicture($url.$file['file']['name']);
                } else {
                    $errors = $uploadedFile->getErrors();
                }
                
                return $this->redirect()->toRoute('zfcuser/home');
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
}