<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Entity\Payments;

class PaymentService implements ServiceLocatorAwareInterface{
    
    protected $em;
    protected $entity = 'Application\Entity\Payments';
    protected $payment;
    protected $oMService;
    protected $userService;
    protected $songService;
    protected $albumService;
    protected $soldSongService;
    protected $actionService;
    
    /**
     * Set the service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return GetAccessLevel
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
    	$this->serviceLocator = $serviceLocator;
    	return $this;
    }
    
    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
    	return $this->serviceLocator;
    }
    
    public function getPayment(){
        return $this->payment;
    }
    
    public function setPayment($payment){
    	$this->payment = $payment;
    }
    
    public function find($id){
        $objectManager = $this->getOMService()->getEntityManager();
         
        return $objectManager->find($this->entity, $id);
    }
    
    public function findAndSet($id){
    	$objectManager = $this->getOMService()->getEntityManager();
    	 
    	$this->country = $objectManager->find($this->entity, $id);
    	
    	return $this->country;
    }
    
    public function findBy($array){
    	$objectManager = $this->getEntityManager();
    	return $objectManager->getRepository($this->entity)->findBy($array);
    }
 
    public function findOneBy($array){
        $objectManager = $this->getOMService()->getEntityManager();
        $user = $objectManager->getRepository($this->entity)->findOneBy($array);
    }
    
    public function query($query,$data){
    	$query = $this->getEntityManager()->createQuery($query);
    
    	foreach ($data as $key => $value){
    		$query->setParameter($key, $value);
    	}
    
    	return $query->getResult();
    }
    
    public function create($amt, $concept, $items, $userId){
        $this->payment = new Payments();
        $objectManager = $this->getOMService()->getEntityManager();
        $user = $this->getUserService()->find($userId);
        $this->payment->populate(
            array(
                'amount' => $amt,
                'concept' => $concept,
                'users' => $user, 
            ));
        foreach ($items as $item){
            $type = explode(' ', $item['name']);
            if($type[0] == 'Song'){
                $this->payment->addSongs($this->searchSong($item));
            }else{
                $this->payment->addAlbum($this->searchAlbum($item));
            }
            
           
        }
        $objectManager->persist($this->payment);
        $objectManager->flush();
        
        $this->getActionService()->create($this->payment->getId(), 'bought', $user);
        
        
    }
    
    public function getPaymentInfo($initialDate,$endDate,$user){
    	$songs = $user->getSongs();
    	$albums = $user->getAlbums();
    	
    	
        
    }
    
    private function searchSongs($items){
    	$songs = array();
    
    	foreach($items as $item){
    		$songs[] = $this->getSongService()->find($item['id']);
    	}
    	return $songs;
    
    }
    
    private function searchSong($item){
    	return $this->getSongService()->find($item['id']);
    }

    private function searchAlbum($item){
    	return $this->getAlbumService()->find($item['id']);
    }
    
    public function getEntityManager()
    {
    	if (null === $this->em) {
    		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	}
    	return $this->em;
    }
    
    private function getOMService()
    {
    	if (! $this->oMService) {
    		$this->oMService = $this->getServiceLocator()->get('Application\Service\DoctrineOMService');
    	}
    
    	return $this->oMService;
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
    
    private function getAlbumService()
    {
    	if (!$this->albumService) {
    		$this->albumService = $this->getServiceLocator()->get('Application\Service\AlbumService');
    	}
    
    	return $this->albumService;
    }
    
    private function getActionService()
    {
    	if (! $this->actionService) {
    		$this->actionService = $this->getServiceLocator()->get('Application\Service\ActionService');
    	}
    
    	return $this->actionService;
    }
    
    /*private function getSoldSongService()
    {
    	if (!$this->soldSongService) {
    		$this->soldSongService = $this->getServiceLocator()->get('Application\Service\SoldSongService');
    	}
    
    	return $this->soldSongService;
    }*/
    
}