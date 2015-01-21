<?php
use Zend\Http\Client;

class Paypal extends Zend_Http_Client{
	
    private $_account = 'lady.eos-facilitator@hotmail.com';
    private $_endpoint = 'api.sandbox.paypal.com';
    private $_client_id = 'AV2_fxDx-P4tT79qYUsekHC7eHENcAM5nAMCTg8k38FyP5R26QkY8QSoNo4G'; // I'm certain you can come up with something to take that into account. :-)
    private $_secret_id = 'EJLa-BDjySo6tDb0YlLU9fe4lnAPiuRQvt7hMvAer5ZSIPAQCVl7rzT1js9A';
    protected $api_base_uri = 'https://api.sandbox.paypal.com';
    protected $payments_uri = '/v1/payments/payment';
    protected $sandbox_auth_url = 'https://api.sandbox.paypal.com/v1/oauth2/token';
    
    protected $access_token = null;
    protected $token_type = null;
    
    
    public function authenticate(){
        $request = new \Zend\Http\Request;
        $request->getHeaders()->addHeaders([
        		'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
        		]);
        $request->setUri($this->sandbox_auth_url);
        $request->setMethod('POST'); //uncomment this if the POST is used
        $request->getPost()->set('u',$this->_client_id.':'.$this->_secret_id);
        $request->getPost()->set('grant_type', 'client_credentials');
        
        
        $client = new \Zend\Http\Client();
        $adapter = new \Zend\Http\Client\Adapter\Curl();
        $client->setAdapter($adapter);
        $adapter->setOptions(array(
        		'curloptions' => array(
        				CURLOPT_URL => 'https://api.sandbox.paypal.com/v1/oauth2/token',
        				CURLOPT_HEADER => false,
        				CURLOPT_SSL_VERIFYPEER => false,
        				CURLOPT_POST => true,
        				CURLOPT_RETURNTRANSFER => true,
        				//CURLOPT_USERPWD  => $this->_client_id.':'.$this->_secret_id,
        				//CURLOPT_POSTFIELDS => "grant_type=client_credentials"
        		)
        ));
        
        $response = $client->dispatch($request);

        if(!$response->isSuccess())die("Error: No response.");

        $json = json_decode($response->getContent());
        return array(
        	'access_token' => $json->access_token,
            'token_type' => $json->token_type
        );
        
    }
    
    
}