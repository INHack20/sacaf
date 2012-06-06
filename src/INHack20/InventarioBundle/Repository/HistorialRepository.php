<?php

namespace INHack20\InventarioBundle\Repository;

use Doctrine\ORM\EntityRepository;
/**
 * Description of HistorialRepository
 *
 * @author INHACK20
 */
class HistorialRepository extends EntityRepository {
   
    public function findLast($comprobante_tipo,$activo_id)
   {
       $query = $this->getEntityManager()->createQuery("SELECT h FROM INHack20InventarioBundle:Historial h 
           WHERE h.comprobantetipo=".$comprobante_tipo." AND h.activo= ".$activo_id."ORDER BY h.creado DESC")
               ->setMaxResults(1);
       try {
            $historial = $query->getSingleResult();
        } catch (\Doctrine\Orm\NoResultException $e) {
            $historial = null;
        }
        return $historial;
   }
}