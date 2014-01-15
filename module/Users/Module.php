<?php
namespace Users;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;
use Zend\Mvc\MvcEvent;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    BootstrapListenerInterface
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            
        );
    }
    
    public function onBootstrap(EventInterface $e)
    {
    	$application   = $e->getApplication();
    	$sm = $application->getServiceManager();
    	$sharedManager = $application->getEventManager()->getSharedManager();
    	 
    	$sharedManager->attach('Zend\Mvc\Application', 'dispatch.error',
    			function($e) use ($sm) {
    				if ($e->getParam('exception')){
    					$sm->get('Zend\Log')->crit($e->getParam('exception'));
    				}
    			}
    	);
    }

}