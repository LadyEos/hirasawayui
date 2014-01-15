<?php
namespace Application\Service;

use Doctrine\ORM\EntityManager;

class DoctrineOMService
{
	protected $entityManager;
    
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}
    
    public function setEntityManager(EntityManager $entityManager) {
		$this->entityManager = $entityManager;
	}
	
	public function getEntityManager(){
		return $this->entityManager;
	}
}