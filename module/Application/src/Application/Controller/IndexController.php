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
//use AuthDoctrine\Form\LoginForm;       // <-- Add this import
//use AuthDoctrine\Form\LoginFilter;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        /* $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        
        $user = new \Application\Entity\Users();
        $user->setUsername('MarcoPivetta');
        $user->setPassword('MarcoPivetta');
        $user->setEmail('MarcoPivetta@mail.com');
        
        $objectManager->persist($user);
        $objectManager->flush();
        
        die(var_dump($user->getId())); */
        
        return new ViewModel();
    }
}
