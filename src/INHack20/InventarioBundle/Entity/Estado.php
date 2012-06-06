<?php

namespace INHack20\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * INHack20\InventarioBundle\Entity\Estado
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Estado
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
     * @ORM\OneToMany(targetEntity="Ubicacion", mappedBy="estado")
     */
    protected $ubicaciones;
    
    public function __construct(){
        $this->ubicaciones= new ArrayCollection();
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
     * @return Estado
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
     * Add ubicaciones
     *
     * @param INHack20\InventarioBundle\Entity\Ubicacion $ubicaciones
     */
    public function addUbicacion(\INHack20\InventarioBundle\Entity\Ubicacion $ubicaciones)
    {
        $this->ubicaciones[] = $ubicaciones;
    }

    /**
     * Get ubicaciones
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUbicaciones()
    {
        return $this->ubicaciones;
    }
}