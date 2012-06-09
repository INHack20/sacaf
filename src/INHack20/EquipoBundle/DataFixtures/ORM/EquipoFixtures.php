<?php

namespace INHack20\EquipoBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use INHack20\EquipoBundle\Entity\TipoComponente;
use INHack20\EquipoBundle\Entity\TipoEquipo;

/**
 * Description of EquipoFixtures
 *
 * @author inhack20
 */
class EquipoFixtures implements FixtureInterface{
    //put your code here
    public function load(ObjectManager $manager) {
        
        $tipoComponente = new TipoComponente();
        $tipoComponente->setDescripcion('TECLADO');
        $manager->persist($tipoComponente);
        
        $tipoComponente = new TipoComponente();
        $tipoComponente->setDescripcion('MOUSE');
        $manager->persist($tipoComponente);
        
        $tipoComponente = new TipoComponente();
        $tipoComponente->setDescripcion('MONITOR');
        $manager->persist($tipoComponente);
        
        $tipoComponente = new TipoComponente();
        $tipoComponente->setDescripcion('UPS');
        $manager->persist($tipoComponente);
        
        $tipoEquipo = new TipoEquipo();
        $tipoEquipo->setDescripcion('PC');
        $manager->persist($tipoEquipo);
        
        $tipoEquipo = new TipoEquipo();
        $tipoEquipo->setDescripcion('LAPTO');
        $manager->persist($tipoEquipo);
        
        $tipoEquipo = new TipoEquipo();
        $tipoEquipo->setDescripcion('IMPRESORA');
        $manager->persist($tipoEquipo);
        
        $tipoEquipo = new TipoEquipo();
        $tipoEquipo->setDescripcion('FOTOCOPIADORA');
        $manager->persist($tipoEquipo);
        
        $tipoEquipo = new TipoEquipo();
        $tipoEquipo->setDescripcion('SWITCHS');
        $manager->persist($tipoEquipo);
        
        $manager->flush();
    }
}

?>