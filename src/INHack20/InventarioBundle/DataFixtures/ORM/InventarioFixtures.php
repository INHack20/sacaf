<?php

namespace INHack20\InventarioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use INHack20\InventarioBundle\Entity\Estado;



/**
 * Description of InventarioFixtures
 *
 * @author inhack20
 */
class InventarioFixtures implements FixtureInterface{
    
public function load(ObjectManager $manager) {
        
        $estado = new Estado();
        $estado->setDescripcion('Anzoátegui');
        $manager->persist($estado);
        
        $estado = new Estado();
        $estado->setDescripcion('Apure');
        $manager->persist($estado);
        
        $estado = new Estado();
        $estado->setDescripcion('Aragua');
        $manager->persist($estado);
        
        $estado = new Estado();
        $estado->setDescripcion('Barinas');
        $manager->persist($estado);
        
        $estado = new Estado();
        $estado->setDescripcion('Bolívar');
        $manager->persist($estado);
        
        $estado = new Estado();
        $estado->setDescripcion('Carabobo');
        $manager->persist($estado);
        
        $estado = new Estado();
        $estado->setDescripcion('Cojedes');
        $manager->persist($estado);
        
        $estado = new Estado();
        $estado->setDescripcion('Delta Amacuro');
        $manager->persist($estado);
                   
        $estado = new Estado();
        $estado->setDescripcion('Distrito Capital');
        $manager->persist($estado);
                    
        $estado = new Estado();
        $estado->setDescripcion('Falcón');
        $manager->persist($estado);
                    
        $estado = new Estado();
        $estado->setDescripcion('Guárico');
        $manager->persist($estado);
                    
        $estado = new Estado();
        $estado->setDescripcion('Lara');
        $manager->persist($estado);
                    
        $estado = new Estado();
        $estado->setDescripcion('Mérida');
        $manager->persist($estado);
                    
        $estado = new Estado();
        $estado->setDescripcion('Miranda');
        $manager->persist($estado);
                    
        $estado = new Estado();
        $estado->setDescripcion('Monagas');
        $manager->persist($estado);
        
        $estado = new Estado();
        $estado->setDescripcion('Nueva Esparta');
        $manager->persist($estado);
        
        $estado = new Estado();
        $estado->setDescripcion('Portuguesa');
        $manager->persist($estado);
        
        $estado = new Estado();
        $estado->setDescripcion('Sucre');
        $manager->persist($estado);
        
        $estado = new Estado();
        $estado->setDescripcion('Táchira');
        $manager->persist($estado);
        
        $estado = new Estado();
        $estado->setDescripcion('Trujillo');
        $manager->persist($estado);
        
        $estado = new Estado();
        $estado->setDescripcion('Vargas');
        $manager->persist($estado);
        
        $estado = new Estado();
        $estado->setDescripcion('Yaracuy');
        $manager->persist($estado);
        
        $estado = new Estado();
        $estado->setDescripcion('Zulia');
        $manager->persist($estado);
        
        $manager->flush();
    }
}

?>