<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Authentication\AuthenticationService;

class Module {
	public function onBootstrap(MvcEvent $e) {
		$eventManager = $e->getApplication ()->getEventManager ();
		$moduleRouteListener = new ModuleRouteListener ();
		$moduleRouteListener->attach ( $eventManager );
		
		$sm = $e->getApplication ()->getServiceManager ();
		
		// Add ACL information to the Navigation view helper
		
		$authorize = $sm->get ( 'BjyAuthorizeServiceAuthorize' );
		$acl = $authorize->getAcl ();
		$role = $authorize->getIdentity ();
		\Zend\View\Helper\Navigation::setDefaultAcl ( $acl );
		\Zend\View\Helper\Navigation::setDefaultRole ( $role );
		
	}
	public function getConfig() {
	    /*
	     * 
	     */
	    $config = array();
	    $configFiles = array(
	    		include __DIR__ . '/config/module.config.php',
	    		include getcwd().'/config/autoload/musiclackey.global.config.php',
	    );
	    foreach ($configFiles as $file) {
	    	$config = \Zend\Stdlib\ArrayUtils::merge($config, $file);
	    }
	    return $config;
	    //*/
	    
	    
	    
		//return include __DIR__ . '/config/module.config.php';
	}
	public function getAutoloaderConfig() {
		return array (
				'Zend\Loader\StandardAutoloader' => array (
						'namespaces' => array (
								__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__ 
						) 
				) 
		);
	}
	public function getServiceConfig() {
		return array (
				'factories' => array (
						'Application\Service\DoctrineOMService' => 'Application\ServiceFactory\DoctrineOMServiceFactory',
						'Zend\Authentication\AuthenticationService' => function ($serviceManager) {
							return $serviceManager->get ( 'doctrine.authenticationservice.orm_default' );
						} 
				),
				'invokables' => array (
					'Application\Service\UserService' => 'Application\Service\UserService',
					'Application\Service\ProfileService' => 'Application\Service\ProfileService',
					'Application\Service\RoleService' => 'Application\Service\RoleService',
					'Application\Service\SongService' => 'Application\Service\SongService',
					'Application\Service\SongCategoriesService' => 'Application\Service\SongCategoriesService',
					'Application\Service\CountryService' => 'Application\Service\CountryService',
					'Application\Service\SongVersionHistoryService' => 'Application\Service\SongVersionHistoryService',
					'Application\Service\PaymentService' => 'Application\Service\PaymentService',
					'Application\Service\PriceService' => 'Application\Service\PriceService',
					'Application\Service\DownloadService' => 'Application\Service\DownloadService',
			        'Application\Service\ActionService' => 'Application\Service\ActionService',
				    'Application\Service\LikeService' => 'Application\Service\LikeService',
				    'Application\Service\GenreService' => 'Application\Service\GenreService',
				    'Application\Service\AlbumService' => 'Application\Service\AlbumService',
				    'Application\Service\Mp3Service' => 'Application\Service\Mp3Service',
				    'Application\Service\MessageService' => 'Application\Service\MessageService',
				) 
		);
	}
	public function getViewHelperConfig() {
		return array (
				'factories' => array (
						'menu' => function ($sm) {
							$locator = $sm->getServiceLocator ();
							$nav = $sm->get ( 'Zend\View\Helper\Navigation' )->menu ( 'navigation' );
							$acl = $locator->get ( 'BjyAuthorize\Service\Authorize' )->getAcl ();
							$role = $locator->get ( 'BjyAuthorize\Service\Authorize' )->getIdentity ();
							$nav->setAcl ( $acl );
							$nav->setRole ( $role );
							$nav->setUseAcl ();
							return $nav->setUlClass ( 'nav' )->setTranslatorTextDomain ( __NAMESPACE__ );
						} 
				)
		);
	}
}
