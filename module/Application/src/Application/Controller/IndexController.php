<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Application\Entity\Users;
use Zend\ServiceManager;
use Doctrine\ORM\Tools\SchemaTool;

class IndexController extends AbstractActionController
{

    protected $doctrineTest;

    protected $oMService;

    public function indexAction()
    {
        /*
         * $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager'); $user = new \Application\Entity\Users(); $user->setUsername('MarcoPivetta'); $user->setPassword('MarcoPivetta'); $user->setEmail('MarcoPivetta@mail.com'); $objectManager->persist($user); $objectManager->flush(); die(var_dump($user->getId()));
         */
        return new ViewModel();
    }

    public function testdoctrineAction()
    {
        $em = $this->getOMService()->getEntityManager();
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $classes = array(
        	
            $em->getClassMetadata('Application\Entity\Actions'),
            $em->getClassMetadata('Application\Entity\Albums'),
            $em->getClassMetadata('Application\Entity\BankAccounts'),
            $em->getClassMetadata('Application\Entity\Countries'),
            $em->getClassMetadata('Application\Entity\Downloads'),
            $em->getClassMetadata('Application\Entity\Genres'),
            $em->getClassMetadata('Application\Entity\GroupChatMessages'),
            $em->getClassMetadata('Application\Entity\GroupChats'),
            $em->getClassMetadata('Application\Entity\Likes'),
            $em->getClassMetadata('Application\Entity\Payments'),
            $em->getClassMetadata('Application\Entity\Prices'),
            $em->getClassMetadata('Application\Entity\PrivateMessages'),
            $em->getClassMetadata('Application\Entity\Role'),
            //$em->getClassMetadata('Application\Entity\SoldSongs'),
            $em->getClassMetadata('Application\Entity\SongCategories'),
            $em->getClassMetadata('Application\Entity\Songs'),
            $em->getClassMetadata('Application\Entity\SongsVersionHistory'),
            $em->getClassMetadata('Application\Entity\UserProfiles'),
            $em->getClassMetadata('Application\Entity\Users')
            
        );
        //$errors = $tool->createSchema($classes);
        $errors = $tool->updateSchema($classes);
        
        print_r($errors);
    }

   /*  public function getDoctrineTest()
    {
        if (! $this->doctrineTest) {
            $sm = $this->getServiceLocator();
            $this->doctrineTest = $sm->get('doctrineTest');
        }
        return $this->doctrineTest;
    } */
    
    

    private function getOMService()
    {
        if (! $this->oMService) {
            $this->oMService = $this->getServiceLocator()->get('Application\Service\DoctrineOMService');
        }
        
        return $this->oMService;
    }
}
