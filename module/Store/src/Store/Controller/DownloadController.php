<?php
namespace Store\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use DoctrineORMModuleTest\Assets\Entity\Date;


class DownloadController extends AbstractActionController
{

    protected $oMService;
    protected $downloadService;
    protected $downloadEntity ='Application\Entity\Downloads';
    protected $songService;
    protected $userService;

    public function indexAction()
    {
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        
        //$downloads = $this->getDownloadService()->findBy(array('user_id'=>$user->getId()));
        $query = 'SELECT d FROM '.$this->downloadEntity.' d WHERE d.user = :user AND d.created < :today AND d.expires > :today';
        $downloads = $this->getDownloadService()->query($query, array('user' => $user,
                                                                    'today'=> new \DateTime()));
        
        //var_dump($downloads);
        
        $this->_view = new ViewModel();
        $this->_view->setVariable('downloads',$downloads);
    	return $this->_view;
    }
    
    public function fileAction()
    {
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        $key = $this->params()->fromRoute('key');
        
        $query = 'SELECT d FROM '.$this->downloadEntity.' d WHERE d.dkey = :key AND d.created < :today AND d.expires > :today';
        $file = $this->getDownloadService()->query($query, array('key' => $key,
                                                                    'today'=> new \DateTime())); 
        
        if(sizeof($file)>0){
            ///*
            //echo $file[0][1];
            //echo $file[0]['file'];
            //$filePath = './public/uploads'.$file[0]['file'];
            $filePath = './public/uploads'.$file[0]->getFile();
            
            $response = new \Zend\Http\Response\Stream();
            $response->setStream(fopen($filePath, 'r'));
            $response->setStatusCode(200);
            
            if($file[0]->getSong() != null){
            
                $song = $file[0]->getSong();
                $author= $this->getAuthorNames($song->getUsers());
                
                $headers = new \Zend\Http\Headers();
                $headers->addHeaderLine('Content-Type', 'audio/mpeg')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $author . ' - ' .$song->getName().
                		substr($file[0]->getFile(), -4). '"')
                		->addHeaderLine('Content-Length', filesize($filePath));
            
            }else{
                $zip = $file[0]->getAlbum();
                $author= $this->getAuthorNames($zip->getUsers());
                
                $headers = new \Zend\Http\Headers();
                $headers->addHeaderLine('Content-Description: File Transfer');
                $headers->addHeaderLine('Content-Type', 'application/octet-stream')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $author . ' - ' .$zip->getName(). '.zip"')
                		->addHeaderLine('Content-Length', filesize($filePath));
            }

            $response->setHeaders($headers);
            return $response;
            
        }
        //echo "error?<br>";
        //var_dump($file);
    	return new ViewModel();
    }

    public function downloadAction(){
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        $song = $this->getSongService()->find($this->params()->fromRoute('id'));
        
        $download = $this->getDownloadService()->create($song->getVersions()->first()->getUrl(),$song,$user);
        
        
        return $this->redirect()->toRoute('download',array('action'=>'file','key'=>$download->getKey()));
    }
    
    private function getAuthorNames($users){
        $authors='';
        $authorNames= array();
        
        foreach($users as $user){
        	array_push($authorNames, $user->getDisplayName());
        }
        
        $size = sizeof($authorNames);
        if($size ==  1){
        	$authors = $authorNames[0];
        }else if($size == 2){
            $authors = $authorNames[0].', '.$authorNames[1];
        }else if($size >= 3){
            for($i = 0;$i<=$size-1;$i++){
        		if($i==$size-1){
        		    $authors .= $authorNames[$i];
        		}else{
        		    $authors .=  $authorNames[$i].', ';
        		}
        	}
        }
        return $authors;
    }
    
    private function getOMService()
    {
    	if (!$this->oMService) {
    		$this->oMService = $this->getServiceLocator()->get('Application\Service\DoctrineOMService');
    	}
    
    	return $this->oMService;
    }
    
    private function getDownloadService(){
    	if (!$this->downloadService) {
    		$this->downloadService = $this->getServiceLocator()->get('Application\Service\DownloadService');
    	}
    
    	return $this->downloadService;
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
    
    
}