<?php

namespace INHack20\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use INHack20\InventarioBundle\Entity\Firma;
use INHack20\InventarioBundle\Entity\Ubicacion;
use INHack20\InventarioBundle\Entity\Estado;

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
        
        $estado = new Estado();
        $estado->setDescripcion('Amazonas');
        $manager->persist($estado);
        
        $ubicacion = new Ubicacion();
        $ubicacion->setCodigo('0');
        $ubicacion->setEstado($estado);
        $ubicacion->setDependencia('DEM');
        $manager->persist($ubicacion);
        
        $firma = new Firma();
        $firma->setNombre('NOMBRE');
        $firma->setApellido('APELLIDO');
        $firma->setCargo('CARGO');
        $firma->setEstado($estado);
        $firma->setUbicacion($ubicacion);
        $manager->persist($firma);
        
        $userManager = $this->container->get('fos_user.user_manager');
        
        $usuario = $userManager->createUser();
        
        $usuario->setNombre('ADMIN');
        $usuario->setApellido('ADMIN');
        
        $usuario->setUsername('admin');
        $usuario->setEmail('admin@admin.com');
        
        $usuario->setEnabled(true);
        
        $usuario->setPlainPassword('adminadmin');
        $usuario->addRole($usuario::ROLE_SUPER_ADMIN); 
        
        $usuario->setEstado($estado);
        $usuario->setFirmaDirector($firma);
        $usuario->setFirmaDivision($firma);
        
        $userManager->updateUser($usuario,true);
        
        $manager->flush();
    }

}