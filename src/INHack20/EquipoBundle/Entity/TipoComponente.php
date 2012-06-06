<?php

namespace INHack20\EquipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * INHack20\EquipoBundle\Entity\TipoComponente
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TipoComponente
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
     * @ORM\OneToMany(targetEntity="Componente", mappedBy="tipoComponente")
     */
    protected $componentes;
    
    public function __construct(){
        $this->componentes= new ArrayCollection();
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
     * @return TipoComponente
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
     * Add componentes
     *
     * @param INHack20\EquipoBundle\Entity\Componente $componentes
     */
    public function addComponente(\INHack20\EquipoBundle\Entity\Componente $componentes)
    {
        $this->componentes[] = $componentes;
    }

    /**
     * Get componentes
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getComponentes()
    {
        return $this->componentes;
    }
}