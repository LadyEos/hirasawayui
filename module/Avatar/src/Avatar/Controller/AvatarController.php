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

class AvatarController extends AbstractActionController
{

    protected $oMService;

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
        
        $objectManager = $this->getOMService()->getEntityManager();
        $id = $this->zfcUserAuthentication()->getIdentity()->getId();
        $user = $objectManager->find('Application\Entity\Users', $id);
        
        if ($user->getUserProfile() == null) {
        	return $this->redirect()->toRoute('zfcuser/addprofile');
        }
        
        $profile = $user->getUserProfile();
        
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
                    // Code to process the file goes here
                    $file = $upload->getFileInfo();
                    //$this->getServiceLocator()->get('Zend\Log')->info($file);
                    
                    //$this->getServiceLocator()->get('Zend\Log')->info($url.$file['file']['name']);
                    
                    $profile->setProfile_picture_url($url.$file['file']['name']);
                    $objectManager->flush();
                    
                } else {
                    $errors = $uploadedFile->getErrors();
                }
                
                return $this->redirect()->toRoute('zfcuser/home');
            }
        }
        
        return array(
            'form' => $form
        );
    }

    private function getOMService()
    {
        if (! $this->oMService) {
            $this->oMService = $this->getServiceLocator()->get('Application\Service\DoctrineOMService');
        }
        
        return $this->oMService;
    }
}