<?php

namespace INHack20\MobiliarioBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MobiliarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MobiliarioRepository extends EntityRepository
{
    public function findHistory()
    {
        return $this->getEntityManager()->createQuery("SELECT m FROM INHack20MobiliarioBundle:Mobiliario m ORDER BY m.actualizado DESC")
                ->setMaxResults(20)
                ->getResult();
    }
    public function findByLike($campo,$valor)
    {
       return $this->getEntityManager()->createQuery("SELECT m FROM INHack20MobiliarioBundle:Mobiliario m WHERE m.$campo LIKE '%$valor%' ORDER BY m.actualizado DESC")
               ;
    }
}