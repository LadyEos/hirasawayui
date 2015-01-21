<?php
// 'Client.php' in '/library/App/Paypal'
namespace Store\Service;

use Store\Service\PaypalClient;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class PaypalClientService implements ServiceLocatorAwareInterface
{
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
	 
	public function create(){
		return new PaypalClient();
	}
	
	public function getToken(){
		
	}
}