<?php

namespace INHack20\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * INHack20\InventarioBundle\Entity\Historial
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="INHack20\InventarioBundle\Repository\HistorialRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Historial
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
     * @ORM\ManyToOne(targetEntity="Ubicacion")
     * @ORM\JoinColumn(name="ubicacion_id", referencedColumnName="id")
     */
    protected $ubicacion;
    
    /**
     * @ORM\ManyToOne(targetEntity="Activo", inversedBy="historiales")
     * @ORM\JoinColumn(name="activo_id", referencedColumnName="id")
     */
    protected $activo;
    
    /**
     * @ORM\Column(name="comprobantetipo", type="integer")
     */
    private $comprobantetipo;
    
    /**
     * @ORM\Column(name="estatusActivo", type="integer")
     */
    private $estatusActivo;
    
    /**
     * @ORM\Column(name="creado", type="datetime")
     */
    protected $creado;
    

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
     * Set creado
     *
     * @param datetime $creado
     * @return Historial
     * @ORM\prePersist
     */
    public function setCreado()
    {
        $this->creado = new \DateTime();
        return $this;
    }

    /**
     * Get creado
     *
     * @return datetime 
     */
    public function getCreado()
    {
        return $this->creado;
    }

    /**
     * Set ubicacion
     *
     * @param INHack20\InventarioBundle\Entity\Ubicacion $ubicacion
     * @return Historial
     */
    public function setUbicacion(\INHack20\InventarioBundle\Entity\Ubicacion $ubicacion = null)
    {
        $this->ubicacion = $ubicacion;
        return $this;
    }

    /**
     * Get ubicacion
     *
     * @return INHack20\InventarioBundle\Entity\Ubicacion 
     */
    public function getUbicacion()
    {
        return $this->ubicacion;
    }

    /**
     * Set activo
     *
     * @param INHack20\InventarioBundle\Entity\Activo $activo
     * @return Historial
     */
    public function setActivo(\INHack20\InventarioBundle\Entity\Activo $activo = null)
    {
        $this->activo = $activo;
        return $this;
    }

    /**
     * Get activo
     *
     * @return INHack20\InventarioBundle\Entity\Activo 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set comprobantetipo
     *
     * @param integer $comprobantetipo
     * @return Historial
     */
    public function setComprobantetipo($comprobantetipo)
    {
        $this->comprobantetipo = $comprobantetipo;
        return $this;
    }

    /**
     * Get comprobantetipo
     *
     * @return integer 
     */
    public function getComprobantetipo()
    {
        return $this->comprobantetipo;
    }

    /**
     * Set estatusActivo
     *
     * @param integer $estatusActivo
     * @return Historial
     */
    public function setEstatusActivo($estatusActivo)
    {
        $this->estatusActivo = $estatusActivo;
        return $this;
    }

    /**
     * Get estatusActivo
     *
     * @return integer 
     */
    public function getEstatusActivo()
    {
        return $this->estatusActivo;
    }
}