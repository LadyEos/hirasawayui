<?php
namespace Application\ServiceFactory;

use Application\Service\DoctrineOMService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\EntityManager;

class DoctrineOMServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

		return new DoctrineOMService($entityManager);
	}
}