<?php
namespace Search\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Search\Form\UserForm;
use Search\Form\UserFilter;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Search\Form\AlbumForm;
use Search\Form\AlbumFilter;

class SearchController extends AbstractActionController
{

    protected $searchService;
    protected $userService;
    protected $genreService;
    protected $albumService;

    public function indexAction()
    {
        $this->_view = new ViewModel();
        
        $topDownloaded = $this->getSearchService()->topDownloaded(5);
        $topLiked = $this->getSearchService()->topLiked(5);
        $topUnfinished = $this->getSearchService()->topUnfinished(5);
        
        $this->_view->setVariable('topDownloaded',$topDownloaded);
        $this->_view->setVariable('topLiked',$topLiked);
        $this->_view->setVariable('topUnfinished',$topUnfinished);
        
        return $this->_view;
    }
    
    public function usersAction()
    {
        /*if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }*/
         
        //$user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        
        
        $results = array();
        $paginator = null;
        $keyword = $this->params()->fromQuery('keyword');
        $genre = $this->params()->fromQuery('genre');
        
        if($genre != null)
            $form = new UserForm('search',$this->getServiceLocator(),$this->getGenreService()->find($genre));
        else 
            $form = new UserForm('search',$this->getServiceLocator());
        
        if($keyword!=null || $genre != null){
            $request = $this->getRequest();
            if ($request->isGet()) {
            	 
            	$form->setInputFilter(new UserFilter($this->getServiceLocator()));
            	$form->setData($request->getQuery());
            	 
            	if ($form->isValid()) {
            		$data = $form->getData();
                    
            		if($data['genre']=="" && $data['keyword'] != ""){
                		$results = $this->getSearchService()->searchUser(array('username'=>'%'.$data['keyword'].'%',
                		'displayname'=>'%'.$data['keyword'].'%',
                		'email'=>'%'.$data['keyword'].'%','name'=>'%'.$data['keyword'].'%','name2'=>'%'.$data['keyword'].'%'));
            		}else if($data['genre'] !="" && $data['keyword'] == ""){
            		    $results = $this->getSearchService()->searchUser(array('genre'=>$data['genre']));
            		}else{
            		    $results = $this->getSearchService()->searchUser(array('username'=>'%'.$data['keyword'].'%',
            		    		'displayname'=>'%'.$data['keyword'].'%',
            		    		'email'=>'%'.$data['keyword'].'%','name'=>'%'.$data['keyword'].'%','name2'=>'%'.$data['keyword'].'%',
            		             'genre'=>$data['genre']));
            		}
            		
            		
            		
            		//return $this->redirect()->toRoute('song',array('action'=>'upload'));
            		$paginator = new Paginator(new ArrayAdapter($results));
            		$paginator->setCurrentPageNumber($this->params()->fromRoute('page'));//$this->params()->fromRoute('page')
            		//$paginator->setCurrentPageNumber($page);
            		//$paginator->setItemCountPerPage(10);
            		$config = $this->getServiceLocator()->get('config');
            		$paginator->setItemCountPerPage($config['MusicLackey']['paginator']['pages']);
            	}
            }
        }
        return array(
        	'form' => $form,
            'keyword' => $keyword,
            'genre' => $genre,
            'paginator' => $paginator
                
        );
    }
    
    public function projectsAction()
    {
    
    	$results = array();
    	$playlist = array();
        $paginator = null;
        $keyword = $this->params()->fromQuery('keyword');
        $genre = $this->params()->fromQuery('genre');
        $config = $this->getServiceLocator()->get('config');
        $djId = $config['MusicLackey']['djRoleId'];
        $playerTime = $config['MusicLackey']['minPlayerTime'];
        
        $authorize = $this->getServiceLocator()->get('BjyAuthorize\Provider\Identity\ProviderInterface');
        $roles = $authorize->getIdentityRoles();
        
        //echo '<br><br><br><br>';
        //var_dump($roles[1]->getId());
        
        if($this->checkId($roles, $djId)){
            $playerTime = 0;
        }
        
        if($genre != null)
            $form = new UserForm('search',$this->getServiceLocator(),$this->getGenreService()->find($genre));
        else 
            $form = new UserForm('search',$this->getServiceLocator());
        
        if($keyword!=null || $genre != null){
            $request = $this->getRequest();
            if ($request->isGet()) {
            	 
            	$form->setInputFilter(new UserFilter($this->getServiceLocator()));
            	$form->setData($request->getQuery());
            	 
            	if ($form->isValid()) {
            		$data = $form->getData();
                    
            		if($data['genre']=="" && $data['keyword'] != ""){
                		$results = $this->getSearchService()->searchSong(array('name'=>'%'.$data['keyword'].'%',
                		  'description'=>'%'.$data['keyword'].'%'),2);
            		}else if($data['genre'] !="" && $data['keyword'] == ""){
            		    $results = $this->getSearchService()->searchSong(array('genre'=>$data['genre']),2);
            		}else{
            		    $results = $this->getSearchService()->searchSong(array('name'=>'%'.$data['keyword'].'%',
                		  'description'=>'%'.$data['keyword'].'%','genre'=>$data['genre']),2);
            		}
            		
            		$playlist = $this->getSearchService()->playlist($results);
            		
            		//return $this->redirect()->toRoute('song',array('action'=>'upload'));
            		$paginator = new Paginator(new ArrayAdapter($results));
            		$paginator->setCurrentPageNumber($this->params()->fromRoute('page'));//$this->params()->fromRoute('page')
            		//$paginator->setCurrentPageNumber($page);
            		//$paginator->setItemCountPerPage(10);
            		$config = $this->getServiceLocator()->get('config');
            		$paginator->setItemCountPerPage($config['MusicLackey']['paginator']['pages']);
            	}
            }
        }
        return array(
        	'form' => $form,
            'keyword' => $keyword,
            'genre' => $genre,
            'paginator' => $paginator,
            'playlist' => $playlist,
            'playerTime' => $playerTime
                
        );
    }

    public function albumsAction()
    {
    
    	$results = array();
    	$paginator = null;
    	$keyword = $this->params()->fromQuery('keyword');
    
    	$form = new AlbumForm('search');
    
    	if($keyword!=null){
    		$request = $this->getRequest();
    		if ($request->isGet()) {
    
    			$form->setInputFilter(new AlbumFilter($this->getServiceLocator()));
    			$form->setData($request->getQuery());
    
    			if ($form->isValid()) {
    				$data = $form->getData();
    				
    				if($data['keyword'] == '' || $data['keyword'] == null ){
    					$results = $this->getAlbumService()->findAll();
    				}else{
    				    $results = $this->getSearchService()->searchAlbum(array('name'=>'%'.$data['keyword'].'%',
    				    		'description'=>'%'.$data['keyword'].'%'));
    				}
    				
    				//return $this->redirect()->toRoute('song',array('action'=>'upload'));
    				$paginator = new Paginator(new ArrayAdapter($results));
    				$paginator->setCurrentPageNumber($this->params()->fromRoute('page'));//$this->params()->fromRoute('page')
    				//$paginator->setCurrentPageNumber($page);
    				//$paginator->setItemCountPerPage(10);
    				$config = $this->getServiceLocator()->get('config');
    				$paginator->setItemCountPerPage($config['MusicLackey']['paginator']['pages']);
    			}
    		}
    	}
    	return array(
    			'form' => $form,
    			'keyword' => $keyword,
    			'paginator' => $paginator
    
    	);
    }
    
    private function getSearchService()
    {
    	if (!$this->searchService) {
    		$this->searchService = $this->getServiceLocator()->get('Search\Service\SearchService');
    	}
    
    	return $this->searchService;
    }
    
    private function getUserService()
    {
    	if (!$this->userService) {
    		$this->userService = $this->getServiceLocator()->get('Application\Service\UserService');
    	}
    
    	return $this->userService;
    }
    
    private function getGenreService()
    {
    	if (!$this->genreService) {
    		$this->genreService = $this->getServiceLocator()->get('Application\Service\GenreService');
    	}
    
    	return $this->genreService;
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