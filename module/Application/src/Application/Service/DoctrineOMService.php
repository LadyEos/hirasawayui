<?php
namespace Application\Service;

use Doctrine\Common\Persistence\ObjectManager;

class DoctrineOMService
{
	protected $entityManager;

	public function __construct(ObjectManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function setEntityManager(ObjectManager $entityManager) {
		$this->entityManager = $entityManager;
	}

	public function getEntityManager(){
		return $this->entityManager;
	}
	
	public function find($entity,$data){
		return $this->entityManager->find($entity,$data);
	}
	
	public function persist($data){
		return $this->entityManager->persist($data);
	}
	
	public function flush(){
		return $this->entityManager->flush();
	}
}