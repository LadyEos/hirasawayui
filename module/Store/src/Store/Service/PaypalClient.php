<?php
// 'Client.php' in '/library/App/Paypal'
namespace Store\Service;

use Zend\Http\Client;



class PaypalClient
{
	private $_api_version = '93';
	 
	// TODO: Originate these values from a secure source.
	private $_api_username = 'lady.eos-facilitator_api1.hotmail.com';
	private $_api_password = '1397002249';
	private $_api_signature = 'ASd0dkLgZDYopJPqz4RKxgl836qBAa6m25HfYEKTPmXAxmbsuZxtdsHW';
	 
	public $API_Endpoint;
	public $PAYPAL_URL;
	public $PAYPAL_DG_URL;
	public $SandboxFlag = true;
	
	protected $request;
	protected $client;
	protected $adapter;
	 
	public function __construct($uri = null, $options = null) {
		//parent::__construct($uri, $options);
		 
	    $this->request = new \Zend\Http\Request;
	    $this->request->getHeaders()->addHeaders([
	    		'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
	    		]);
	    $this->request->setMethod('POST'); //uncomment this if the POST is used
	    $this->request->getPost()->set('USER',$this->_api_username);
	    $this->request->getPost()->set('PWD', $this->_api_password);
	    //$this->request->getPost()->set('grant_type', 'client_credentials');
	    $this->request->getPost()->set('SIGNATURE', $this->_api_signature);
	    $this->request->getPost()->set('VERSION', $this->_api_version);
	    

		if ($this->SandboxFlag == true)
		{
			$this->API_Endpoint      = 'https://api-3t.sandbox.paypal.com/nvp';
			$this->PAYPAL_URL      = 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=';
			$this->PAYPAL_DG_URL   = 'https://www.sandbox.paypal.com/incontext?token=';
		}
		else
		{
			$this->API_Endpoint      = 'https://api-3t.paypal.com/nvp';
			$this->PAYPAL_URL      = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';
			$this->PAYPAL_DG_URL   = 'https://www.paypal.com/incontext?token=';
		}

		$this->request->setUri($this->API_Endpoint);
	}
	 
	/*
	 * Request an authorization token.
	*
	* @param float $paymentAmount
	* @param string $returnURL
	* @param string $cancelURL
	* @param string $currencyID
	* @param string $paymentType Can be 'Authorization', 'Sale', or 'Order'. Default is 'Authorization'
	* @return Zend_Http_Response
	*/
	public function ecSetExpressCheckout($paymentAmount, $returnURL, $cancelURL, $currencyID, $items, $paymentType = 'Authorization') {
	    $this->request->getPost()->set('METHOD', 'SetExpressCheckout');
	    $this->request->getPost()->set('PAYMENTREQUEST_0_AMT', $paymentAmount);
	    $this->request->getPost()->set('PAYMENTREQUEST_0_ITEMAMT', $paymentAmount);
	    $this->request->getPost()->set('PAYMENTREQUEST_0_PAYMENTACTION',  $paymentType);
	    $this->request->getPost()->set('RETURNURL', $returnURL);
	    $this->request->getPost()->set('CANCELURL', $cancelURL);
	    $this->request->getPost()->set('PAYMENTREQUEST_0_CURRENCYCODE', $currencyID);
	    $this->request->getPost()->set('REQCONFIRMSHIPPING', 0);
	    $this->request->getPost()->set('NOSHIPPING', 1);

	    foreach ($items as $index => $item) {
	    	$this->request->getPost()->set('L_PAYMENTREQUEST_0_NAME'. $index, $item['name']);
	    	$this->request->getPost()->set('L_PAYMENTREQUEST_0_AMT'. $index, $item['amt']);
	    	$this->request->getPost()->set('L_PAYMENTREQUEST_0_QTY'. $index, $item['qty']);
	    	$this->request->getPost()->set('L_PAYMENTREQUEST_0_ITEMCATEGORY'. $index, 'Digital');
	    }

		$this->client = new \Zend\Http\Client();
		$this->adapter = new \Zend\Http\Client\Adapter\Curl();
		$this->client->setAdapter($this->adapter);

		$this->adapter->setOptions(array(
				'curloptions' => array(
						//CURLOPT_URL => 'https://api.sandbox.paypal.com/v1/oauth2/token',
						CURLOPT_HEADER => false,
						CURLOPT_SSL_VERIFYPEER => false,
						CURLOPT_POST => true,
						CURLOPT_RETURNTRANSFER => true,
						//CURLOPT_USERPWD  => $this->_client_id.':'.$this->_secret_id,
						//CURLOPT_POSTFIELDS => "grant_type=client_credentials"
				)
		));
		//var_dump($this->request->getPost());
		return $this->client->dispatch($this->request);
	}
	 

	public function ecGetExpressCheckoutDetails($token){
	    
	    $this->request->getPost()->set('METHOD', 'GetExpressCheckoutDetails');
		$this->request->getPost()->set('TOKEN', $token);

		$this->client = new \Zend\Http\Client();
		$this->adapter = new \Zend\Http\Client\Adapter\Curl();
		$this->client->setAdapter($this->adapter);
	
		$this->adapter->setOptions(array(
				'curloptions' => array(
						//CURLOPT_URL => 'https://api.sandbox.paypal.com/v1/oauth2/token',
						CURLOPT_HEADER => false,
						CURLOPT_SSL_VERIFYPEER => false,
						CURLOPT_POST => true,
						CURLOPT_RETURNTRANSFER => true,
						//CURLOPT_USERPWD  => $this->_client_id.':'.$this->_secret_id,
						//CURLOPT_POSTFIELDS => "grant_type=client_credentials"
				)
		));
	
		return $this->client->dispatch($this->request);
	}
	
	
	
	public function ecDoExpressCheckoutPayment($token, $payersId, $amount, $currency){
		 
		$this->request->getPost()->set('METHOD', 'DoExpressCheckoutPayment');
		$this->request->getPost()->set('TOKEN', $token);
		$this->request->getPost()->set('PAYERID', $payersId);
		$this->request->getPost()->set('PAYMENTREQUEST_0_PAYMENTACTION', 'SALE');
		$this->request->getPost()->set('PAYMENTREQUEST_0_AMT', $amount);
		$this->request->getPost()->set('PAYMENTREQUEST_0_CURRENCYCODE', $currency);
		
		$this->client = new \Zend\Http\Client();
		$this->adapter = new \Zend\Http\Client\Adapter\Curl();
		$this->client->setAdapter($this->adapter);
	
		$this->adapter->setOptions(array(
				'curloptions' => array(
						//CURLOPT_URL => 'https://api.sandbox.paypal.com/v1/oauth2/token',
						CURLOPT_HEADER => false,
						CURLOPT_SSL_VERIFYPEER => false,
						CURLOPT_POST => true,
						CURLOPT_RETURNTRANSFER => true,
						//CURLOPT_USERPWD  => $this->_client_id.':'.$this->_secret_id,
						//CURLOPT_POSTFIELDS => "grant_type=client_credentials"
				)
		));
	
		return $this->client->dispatch($this->request);
	}
	
	/*
	 * Parse a Name-Value Pair response into an object.
	* @param string $response
	* @return object Returns an object representation of the response.
	*/
	public static function parse($response) {
		 
		$result = array();
		parse_str($response, $result);
		 
		if (empty($result))
			return NULL;
		 
		return (object) $result;
	}
}