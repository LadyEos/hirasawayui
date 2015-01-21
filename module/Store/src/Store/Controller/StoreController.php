<?php
namespace Store\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Application\Entity\Users;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Store\Form\CheckOutForm;
use Store\Form\SubmitForm;
use Zend\Session\Container;
use Doctrine\DBAL\Types\VarDateTimeType;

class StoreController extends AbstractActionController
{

    protected $oMService;
    protected $userService;
    protected $songService;
    protected $albumService;
    protected $songVersionHistoryService;
    protected $payment;
    protected $paypalClient;
    protected $downloadService;

    public function indexAction()
    {
    	return new ViewModel();
    }
    
    public function listAction()
    {
        /*if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }*/
        
        //$user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        
        $this->_view = new ViewModel();
        
        $songs = $this->getSongService()->findBy(array('completed'=>1,'sample'=>0));
        
        
        $paginator = new Paginator(new ArrayAdapter($songs));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));//$this->params()->fromRoute('page')
        //$paginator->setCurrentPageNumber($page);
        //$paginator->setItemCountPerPage(10);
        $config = $this->getServiceLocator()->get('config');
        $paginator->setItemCountPerPage($config['MusicLackey']['paginator']['pages']);
        
        $this->_view->setVariable('paginator',$paginator);
        //$this->getServiceLocator()->get('Zend\Log')->info("yei");
        return $this->_view;
    }
    
    public function albumAction()
    {
    	/*if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}*/
    
    	//$user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
    
    	$this->_view = new ViewModel();
    
    	$albums = $this->getAlbumService()->findBy(array('completed'=>1,'active'=>1));
    
    
    	$paginator = new Paginator(new ArrayAdapter($albums));
    	$paginator->setCurrentPageNumber($this->params()->fromRoute('page'));//$this->params()->fromRoute('page')
    	//$paginator->setCurrentPageNumber($page);
    	//$paginator->setItemCountPerPage(10);
    	
    	$config = $this->getServiceLocator()->get('config');
    	$paginator->setItemCountPerPage($config['MusicLackey']['paginator']['pages']);
    
    	$this->_view->setVariable('paginator',$paginator);
    	
    	//$this->getServiceLocator()->get('Zend\Log')->info("yei");
    	return $this->_view;
    }
    
    public function buyAction(){
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        //$this->getServiceLocator()->get('Zend\Log')->info("1");
        $songId = $this->params()->fromRoute('id');
        
        $shoppingCart = $this->ShoppingCart();
        
        $song = $this->getSongService()->find($songId);
        
        $config = $this->getServiceLocator()->get('config');
        $amount = $config['MusicLackey']['songPrice'];
        $qty = $config['MusicLackey']['qty'];
        
        $order = array(
            'id'      => $songId,
            'qty'     => $qty,
            'price'   => $amount,
            'product' => 'Song - '.$song->getName());
        $this->ShoppingCart()->insert($order);
        
        return $this->redirect()->toRoute('store',array('action'=>'cart'));
        
    }
    
    public function buyAlbumAction(){
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    
    	$user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
    	//$this->getServiceLocator()->get('Zend\Log')->info("1");
    	$albumId = $this->params()->fromRoute('id');
    
    	$shoppingCart = $this->ShoppingCart();
    	
    	$config = $this->getServiceLocator()->get('config');
    	$amount =  $config['MusicLackey']['albumSongPrice'];
    	$qty = $config['MusicLackey']['qty'];
    
    	$album = $this->getAlbumService()->find($albumId);
        $amountTotal = ($amount * $album->countSongs())*1.8;
    	$order = array(
    			'id'      => $albumId,
    			'qty'     => $qty,
    			'price'   => $amountTotal,
    			'product' => 'Album - '.$album->getName());
    	$this->ShoppingCart()->insert($order);
    
    	return $this->redirect()->toRoute('store',array('action'=>'cart'));
    
    }
    
    public function cartAction(){
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        $this->getServiceLocator()->get('Zend\Log')->info("cart");
        
        $form = new CheckOutForm('CheckOut');
        
        return new ViewModel(array(
            'form' => $form,
            'cart' => $this->ShoppingCart()->cart(),
            'total_items' => $this->ShoppingCart()->total_items(),
            'total_sum' => number_format((float)$this->ShoppingCart()->total_sum(), 2, '.', ''),
        ));
        
    }
    
    public function removeAction(){
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        //$this->getServiceLocator()->get('Zend\Log')->info("remove");
        
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        $token = $this->params()->fromRoute('token');
        echo $token;
        //$this->getServiceLocator()->get('Zend\Log')->info("remove token".$token);
        
        $this->ShoppingCart()->remove($token);
        
        return $this->redirect()->toRoute('store',array('action'=>'cart'));
        
        //return new ViewModel();
    }
    
    public function checkoutAction(){
        /*if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        */
        $adapter = $this->getPaypalClientService()->create();
        
        $view = new ViewModel(array(
            'message' => 'Hello world',
    	    'adapter' => $adapter
        ));

        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);
        
        //$adapter = $this->adapter;
        //echo $adapter;
        $paymentAmount = number_format((float)$this->ShoppingCart()->total_sum(), 2, '.', '');
        $currencyCodeType = 'USD';
        $paymentType = 'Sale';
        $returnURL = 'http://' . $_SERVER['HTTP_HOST'] . '/store/confirm';
        $cancelURL = 'http://' . $_SERVER['HTTP_HOST'] . '/store/cancel';
        
        $items = array();
        
        foreach ($this->ShoppingCart()->cart() as $item_value ){
            $items[] = array(
            		'name'   => $item_value->getProduct(),
            		'amt'   => $item_value->getPrice(),
            		'qty'   => $item_value->getQty(),
                    'id'    => $item_value->getId()
            );
        }
        
        /*echo sizeof($items).'<br>';
        echo $paymentAmount.' PA<br>';
        var_dump($items);*/
        
        // Obtain a token.
        $reply = $adapter->ecSetExpressCheckout($paymentAmount, $returnURL, $cancelURL, $currencyCodeType, $items, $paymentType);
        // If we succeed, we must redirect to PayPal at this point.
        if ($reply->isSuccess()) {
        	//$json = json_decode($reply->getContent());
        
        	//var_dump($adapter->parse($reply));
        	 
        	// Let's turn that message body into something we can use...
        	$replyData   = $adapter->parse($reply->getBody());
        	$ack      = strtoupper($replyData->ACK);
        	// If we did in fact succeed, we will now have a token to use
        	if ($ack == 'SUCCESS' || $ack == 'SUCCESSWITHWARNING') {
        		
        	    $session = new Container('Payment');
        	    $session->CHECKOUT_AMOUNT = $paymentAmount;
        	    $session->TOKEN = strtoupper($replyData->TOKEN);
                $session->CURRENCY = $currencyCodeType;
                $session->ITEMS = $items;
                
        	    $this->redirect()->toUrl($adapter->PAYPAL_DG_URL. urldecode($replyData->TOKEN));
        	}else{
        	    echo "ajsdlkajsldkas<br>";
        	    var_dump($adapter->parse($reply->getBody()));
        	}
        	//*/
        }
        else{
        	echo 'omg error!';
        	throw new Exception('ECSetExpressCheckout: We failed to get a successful response from PayPal.');
        
        }

        
        return $view;
    }
    
    public function confirmAction(){
    	if (! $this->zfcUserAuthentication()->hasIdentity()) {
    		return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    	}
    
    	$user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
    	$adapter = $this->getPaypalClientService()->create();
    
    	$session = new Container('Payment');
    	$checkoutAmount = $session->CHECKOUT_AMOUNT;
    	$token = $session->TOKEN;
    	$currency = $session->CURRENCY;
    	$items = $session->ITEMS;
    	//$cart = $session->cart;
    
    	$form = new SubmitForm('Confirm');
    	$reply = $adapter->ecGetExpressCheckoutDetails($token);
    	if ($reply->isSuccess()) {
    
    		$replyData   = $adapter->parse($reply->getBody());
    		$ack      = strtoupper($replyData->ACK);

    		if ($ack == 'SUCCESS' || $ack == 'SUCCESSWITHWARNING') {
    			$payerId = strtoupper($replyData->PAYERID);
    			$request = $this->getRequest();
    			if ($request->isPost()) {
    				$form->setData($request->getPost());
    				 
    				if ($form->isValid()) {
    					$adapter = $this->getPaypalClientService()->create();
    
    					$replyPayment = $adapter->ecDoExpressCheckoutPayment($token,$payerId, $checkoutAmount, $currency );
    
    					// If we succeed, we must redirect to PayPal at this point.
    					if ($replyPayment->isSuccess()) {
    						$replyPaymentData = $adapter->parse($replyPayment->getBody());
    						$ack = strtoupper($replyPaymentData->ACK);
    							
    						//var_dump($replyPaymentData);
    							
    						if ($ack == 'SUCCESS' || $ack == 'SUCCESSWITHWARNING') {
    							$this->getPaymentService()->create($checkoutAmount, 'digital download', $items, $this->zfcUserAuthentication()->getIdentity()->getId());
    							$this->ShoppingCart()->destroy();
    
    							return $this->redirect()->toRoute('store',array('action'=>'thankyou'));
    						}
    					}else{
    						echo 'omg error2!';
    						var_dump($adapter->parse($replyPayment->getBody()));
    						throw new Exception('ecGetExpressCheckoutDetails: We failed to get a successful response from PayPal.');
    						 
    					}
    				}
    			}
    			return array(
    					'form' => $form,
    					'cart'=>$items,
    			);
    
    		}else{
    			echo 'omg error1!';
    			var_dump($adapter->parse($reply->getBody()));
    			throw new Exception('ecGetExpressCheckoutDetails:Faulire');
    
    		}
    		//*/
    	}else{
    		echo 'omg error!';
    		var_dump($adapter->parse($reply->getBody()));
    		throw new Exception('ecGetExpressCheckoutDetails: We failed to get a successful response from PayPal.');
    
    	}
    
    
    	//return new ViewModel();
    }
    
    public function thankyouAction(){
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        $session = new Container('Payment');
        $items = $session->ITEMS;
        
        $form = new SubmitForm('Go to Downloads');
        $request = $this->getRequest();
        if ($request->isPost()) {
        	$form->setData($request->getPost());
        	 
        	if ($form->isValid()) {
        	    $this->getServiceLocator()->get('Zend\Log')->info('THIS HAPPENS');
        	    $this->getServiceLocator()->get('Zend\Log')->info('---------------------  '.sizeof($items));
        	    foreach ($items as $item){
        	    
        	    	//var_dump($item);
        	    	$type = explode(' ', $item['name']);
        	    	if($type[0] == 'Song'){
        	    		$song = $this->getSongService()->find($item['id']);
        	    		$this->getServiceLocator()->get('Zend\Log')->info('song  '.$song->getName().' '.$song->getId());
        	    		//var_dump($songs);
        	    		$this->getDownloadService()->create($song->getVersions()->first()->getUrl(),$song,$user);
        	    	}else{
        	    		$album = $this->getAlbumService()->find($item['id']);
        	    		$this->getServiceLocator()->get('Zend\Log')->info('album  '.$album->getName().' '.$album->getId());
        	    		//var_dump($songs);
        	    		//TODO: get validation for when zip file could not be created
        	    		$this->getDownloadService()->createAlbum($album,$user);
        	    	}
        	    }
        	    
        	    return $this->redirect()->toRoute('store',array('action'=>'close'));
        	}
        }
        return array(
        	    	'form' => $form,
        	    );
    }
    
    
    public function historyAction(){
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
        
        $purchases = $this->getPaymentService()->findBy(array('users'=>$user->getId()));
        
        
        $this->_view = new ViewModel();
        $paginator = new Paginator(new ArrayAdapter($purchases));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));//$this->params()->fromRoute('page')
        //$paginator->setCurrentPageNumber($page);
        //$paginator->setItemCountPerPage(10);
        $config = $this->getServiceLocator()->get('config');
        $paginator->setItemCountPerPage($config['MusicLackey']['paginator']['pages']);
        
        $this->_view->setVariable('paginator',$paginator);
        return $this->_view;
        
        return new ViewModel();
    }
    
    public function zipfileAction(){
        
        if (! $this->zfcUserAuthentication()->hasIdentity()) {
        	return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        
        $user = $this->getUserService()->findAndSetUser($this->zfcUserAuthentication()->getIdentity()->getId());
    	$this->_view = new ViewModel();
    
    	$album = $this->getAlbumService()->find(15);
        
    	$this->getDownloadService()->createZipfile($album);
    	
    	return $this->_view;
    }
    
    public function closeAction(){
        return new ViewModel();
    }
    
    private function getOMService()
    {
    	if (!$this->oMService) {
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
    
    private function getSongVersionHistoryService()
    {
    	if (!$this->songVersionHistoryService) {
    		$this->songVersionHistoryService = $this->getServiceLocator()->get('Application\Service\SongVersionHistoryService');
    	}
    
    	return $this->songVersionHistoryService;
    }
    
    private function getPaymentService()
    {
    	if (!$this->payment) {
    		$this->payment = $this->getServiceLocator()->get('Application\Service\PaymentService');
    	}
    
    	return $this->payment;
    }
    
    private function getPaypalClientService(){
        if (!$this->paypalClient) {
        	$this->paypalClient = $this->getServiceLocator()->get('Store\Service\PaypalClientService');
        }
        
        return $this->paypalClient;
    }
    
    private function getDownloadService(){
    	if (!$this->downloadService) {
    		$this->downloadService = $this->getServiceLocator()->get('Application\Service\DownloadService');
    	}
    
    	return $this->downloadService;
    }
}