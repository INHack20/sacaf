<?php

namespace INHack20\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * INHack20\InventarioBundle\Entity\Activo
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Activo
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
     * @var string $nroBienNacional
     *
     * @ORM\Column(name="nroBienNacional", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $nroBienNacional;

    /**
     * @var text $observacion
     *
     * @ORM\Column(name="observacion", type="text", nullable=true)
     */
    private $observacion;
    
    /**
     * @ORM\Column(name="tipoactivo", type="integer")
     */
    protected $tipoactivo;

    /**
     * @ORM\ManyToOne(targetEntity="Orden", inversedBy="activos")
     * @ORM\JoinColumn(name="orden_id", referencedColumnName="id")
     */
    protected $orden;
    
    /**
     *@ORM\Column(name="creado",type="datetime")
     */
    private $creado;
    
    /**
     * @ORM\Column(name="actualizado",type="datetime", nullable=true)
     */
    private $actualizado;
    
    /**
     * @ORM\Column(name="estatus", type="integer")
     */
    protected $estatus;
    
    //Activos Asociados
    
    /**
     * @ORM\OneToOne(targetEntity="INHack20\MobiliarioBundle\Entity\Mobiliario", mappedBy="activo")
     */
    protected $mobiliario;
    
    /**
     * @ORM\OneToOne(targetEntity="INHack20\EquipoBundle\Entity\Equipo", mappedBy="activo")
     */
    protected $equipo;

    /**
     * @ORM\ManyToMany(targetEntity="Comprobante", mappedBy="activos")
     */
    private $comprobantes;
    
    /**
     * @ORM\OneToMany(targetEntity="Historial", mappedBy="activo")
     */
    private $historiales;
    
    /**
     * @ORM\ManyToOne(targetEntity="Ubicacion", inversedBy="activos")
     * @ORM\JoinColumn(name="ubicacion_id", referencedColumnName="id")
     */
    private $ubicacion;
    
    public function __construct(){
        $this->comprobantes= new ArrayCollection();
        $this->historiales= new ArrayCollection();
    }
    
    /**
     * Set actualizado
     * @ORM\preUpdate
     */
    public function setActualizado()
    {
        $this->actualizado = new \DateTime;
    }

    /**
     * Get actualizado
     *
     * @return datetime 
     */
    public function getActualizado()
    {
        return $this->actualizado;
    }

    /**
     * Set creado
     * @ORM\prePersist
     */
    public function setCreado()
    {
        $this->creado = new \DateTime;
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nroBienNacional
     *
     * @param string $nroBienNacional
     * @return Activo
     */
    public function setNroBienNacional($nroBienNacional)
    {
        $this->nroBienNacional = $nroBienNacional;
        return $this;
    }

    /**
     * Get nroBienNacional
     *
     * @return string 
     */
    public function getNroBienNacional()
    {
        return $this->nroBienNacional;
    }

    /**
     * Set observacion
     *
     * @param text $observacion
     * @return Activo
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;
        return $this;
    }

    /**
     * Get observacion
     *
     * @return text 
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set orden
     *
     * @param INHack20\InventarioBundle\Entity\Orden $orden
     * @return Activo
     */
    public function setOrden(\INHack20\InventarioBundle\Entity\Orden $orden = null)
    {
        $this->orden = $orden;
        return $this;
    }

    /**
     * Get orden
     *
     * @return INHack20\InventarioBundle\Entity\Orden 
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set mobiliario
     *
     * @param INHack20\MobiliarioBundle\Entity\Mobiliario $mobiliario
     * @return Activo
     */
    public function setMobiliario(\INHack20\MobiliarioBundle\Entity\Mobiliario $mobiliario = null)
    {
        $this->mobiliario = $mobiliario;
        return $this;
    }

    /**
     * Get mobiliario
     *
     * @return INHack20\MobiliarioBundle\Entity\Mobiliario 
     */
    public function getMobiliario()
    {
        return $this->mobiliario;
    }

    /**
     * Set equipo
     *
     * @param INHack20\EquipoBundle\Entity\Equipo $equipo
     * @return Activo
     */
    public function setEquipo(\INHack20\EquipoBundle\Entity\Equipo $equipo = null)
    {
        $this->equipo = $equipo;
        return $this;
    }

    /**
     * Get equipo
     *
     * @return INHack20\EquipoBundle\Entity\Equipo 
     */
    public function getEquipo()
    {
        return $this->equipo;
    }

    /**
     * Add comprobantes
     *
     * @param INHack20\InventarioBundle\Entity\Comprobante $comprobantes
     */
    public function addComprobante(\INHack20\InventarioBundle\Entity\Comprobante $comprobantes)
    {
        $this->comprobantes[] = $comprobantes;
    }

    /**
     * Get comprobantes
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getComprobantes()
    {
        return $this->comprobantes;
    }

    /**
     * Set ubicacion
     *
     * @param INHack20\InventarioBundle\Entity\Ubicacion $ubicacion
     * @return Activo
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
     * Add historiales
     *
     * @param INHack20\InventarioBundle\Entity\Historial $historiales
     */
    public function addHistorial(\INHack20\InventarioBundle\Entity\Historial $historiales)
    {
        $this->historiales[] = $historiales;
    }

    /**
     * Get historiales
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getHistoriales()
    {
        return $this->historiales;
    }

    /**
     * Set estatus
     *
     * @param integer $estatus
     * @return Activo
     */
    public function setEstatus($estatus)
    {
        $this->estatus = $estatus;
        return $this;
    }

    /**
     * Get estatus
     *
     * @return integer 
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
     * Set tipoactivo
     *
     * @param integer $tipoactivo
     * @return Activo
     */
    public function setTipoactivo($tipoactivo)
    {
        $this->tipoactivo = $tipoactivo;
        return $this;
    }

    /**
     * Get tipoactivo
     *
     * @return integer 
     */
    public function getTipoactivo()
    {
        return $this->tipoactivo;
    }
}