<?php

namespace INHack20\EquipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * INHack20\EquipoBundle\Entity\TipoEquipo
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TipoEquipo
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\OneToMany(targetEntity="Equipo", mappedBy="tipoEquipo")
     */
    protected $equipos;
    
    public function __construct(){
        $this->equipos=new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return TipoEquipo
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Add equipos
     *
     * @param INHack20\EquipoBundle\Entity\Equipo $equipos
     */
    public function addEquipo(\INHack20\EquipoBundle\Entity\Equipo $equipos)
    {
        $this->equipos[] = $equipos;
    }

    /**
     * Get equipos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEquipos()
    {
        return $this->equipos;
    }
}