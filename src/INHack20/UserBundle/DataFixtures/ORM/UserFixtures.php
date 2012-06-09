<?php

namespace INHack20\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of UserFixtures
 *
 * @author inhack20
 */
class UserFixtures implements FixtureInterface, ContainerAwareInterface{
    
    /**
     * @var ContainerInterface
     */
    private $container;
    
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }
    
    public function load(ObjectManager $manager) {
        
        $userManager = $this->container->get('fos_user.user_manager');
        
        $usuario = $userManager->createUser();
        
        $usuario->setNombre('ADMIN');
        $usuario->setApellido('ADMIN');
        
        $usuario->setUsername('admin');
        $usuario->setEmail('admin@admin.com');
        
        $usuario->setEnabled(true);
        
        $usuario->setPlainPassword('adminadmin');
        $usuario->addRole($usuario::ROLE_SUPER_ADMIN); 
        
        $userManager->updateUser($usuario,true);
    }

}