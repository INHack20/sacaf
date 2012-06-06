<?php

namespace INHack20\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * INHack20\InventarioBundle\Entity\Orden
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Orden
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
     * @var integer $orden_compra
     *
     * @ORM\Column(name="ordenCompra", type="integer")
     * @Assert\NotBlank()
     */
    private $ordenCompra;

    /**
     * @var date $fecha_compra
     *
     * @ORM\Column(name="fechaCompra", type="date")
     * @Assert\NotBlank()
     * @Assert\Date(message = "Este campo debe ser una fecha")
     */
    private $fechaCompra;

    /**
     * @var string $empresa
     *
     * @ORM\Column(name="empresa", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $empresa;

    /**
     * @var integer $factura
     *
     * @ORM\Column(name="factura", type="integer")
     * @Assert\NotBlank()
     */
    private $factura;

    /**
     * @var date $fecha_factura
     *
     * @ORM\Column(name="fechaFactura", type="date")
     * @Assert\NotBlank()
     * @Assert\Date(message="Este campo debe ser una fecha")
     */
    private $fechaFactura;

    /**
     * @var string $acta_recepcion
     *
     * @ORM\Column(name="actaRecepcion", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $actaRecepcion;

    /**
     * @var date $acta_recepcion_fecha
     *
     * @ORM\Column(name="actaRecepcionFecha", type="date")
     * @Assert\NotBlank()
     * @Assert\Date(message="Este campo debe ser una fecha")
     */
    private $actaRecepcionFecha;
    
    /**
     * @ORM\Column(name="tipoActivo", type="integer")
     * @Assert\NotBlank()
     */
    private $tipoActivo;
    
    /**
     * @ORM\oneToMany(targetEntity="Activo", mappedBy="orden")
     */
    protected $activos;
    
    /**
     *@ORM\Column(name="creado",type="datetime")
     */
    private $creado;
    
    /**
     * @ORM\Column(name="actualizado",type="datetime", nullable=true)
     */
    private $actualizado;
    
    /**
     * @ORM\ManyToOne(targetEntity="Estado")
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id")
     */
    private $estado;
    
    public function __contruct()
    {
        $this->orden= new ArrayCollection();
    }
    public function __construct()
    {
        $this->activos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set empresa
     *
     * @param string $empresa
     * @return Orden
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
        return $this;
    }

    /**
     * Get empresa
     *
     * @return string 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set factura
     *
     * @param integer $factura
     * @return Orden
     */
    public function setFactura($factura)
    {
        $this->factura = $factura;
        return $this;
    }

    /**
     * Get factura
     *
     * @return integer 
     */
    public function getFactura()
    {
        return $this->factura;
    }

    /**
     * Set creado
     *
     * @param datetime $creado
     * @return Orden
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
     * Set actualizado
     *
     * @param datetime $actualizado
     * @return Orden
     * @ORM\preUpdate
     */
    public function setActualizado()
    {
        $this->actualizado = new \DateTime();
        return $this;
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
     * Add activos
     *
     * @param INHack20\InventarioBundle\Entity\Activo $activos
     */
    public function addActivo(\INHack20\InventarioBundle\Entity\Activo $activos)
    {
        $this->activos[] = $activos;
    }

    /**
     * Get activos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getActivos()
    {
        return $this->activos;
    }

    /**
     * Set ordenCompra
     *
     * @param integer $ordenCompra
     * @return Orden
     */
    public function setOrdenCompra($ordenCompra)
    {
        $this->ordenCompra = $ordenCompra;
        return $this;
    }

    /**
     * Get ordenCompra
     *
     * @return integer 
     */
    public function getOrdenCompra()
    {
        return $this->ordenCompra;
    }

    /**
     * Set fechaCompra
     *
     * @param date $fechaCompra
     * @return Orden
     */
    public function setFechaCompra($fechaCompra)
    {
        $this->fechaCompra = $fechaCompra;
        return $this;
    }

    /**
     * Get fechaCompra
     *
     * @return date 
     */
    public function getFechaCompra()
    {
        return $this->fechaCompra;
    }

    /**
     * Set fechaFactura
     *
     * @param date $fechaFactura
     * @return Orden
     */
    public function setFechaFactura($fechaFactura)
    {
        $this->fechaFactura = $fechaFactura;
        return $this;
    }

    /**
     * Get fechaFactura
     *
     * @return date 
     */
    public function getFechaFactura()
    {
        return $this->fechaFactura;
    }

    /**
     * Set actaRecepcion
     *
     * @param string $actaRecepcion
     * @return Orden
     */
    public function setActaRecepcion($actaRecepcion)
    {
        $this->actaRecepcion = $actaRecepcion;
        return $this;
    }

    /**
     * Get actaRecepcion
     *
     * @return string 
     */
    public function getActaRecepcion()
    {
        return $this->actaRecepcion;
    }

    /**
     * Set actaRecepcionFecha
     *
     * @param date $actaRecepcionFecha
     * @return Orden
     */
    public function setActaRecepcionFecha($actaRecepcionFecha)
    {
        $this->actaRecepcionFecha = $actaRecepcionFecha;
        return $this;
    }

    /**
     * Get actaRecepcionFecha
     *
     * @return date 
     */
    public function getActaRecepcionFecha()
    {
        return $this->actaRecepcionFecha;
    }

    /**
     * Set tipoActivo
     *
     * @param integer $tipoActivo
     * @return Orden
     */
    public function setTipoActivo($tipoActivo)
    {
        $this->tipoActivo = $tipoActivo;
        return $this;
    }

    /**
     * Get tipoActivo
     *
     * @return integer 
     */
    public function getTipoActivo()
    {
        return $this->tipoActivo;
    }

    /**
     * Set estado
     *
     * @param INHack20\InventarioBundle\Entity\Estado $estado
     * @return Orden
     */
    public function setEstado(\INHack20\InventarioBundle\Entity\Estado $estado = null)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * Get estado
     *
     * @return INHack20\InventarioBundle\Entity\Estado 
     */
    public function getEstado()
    {
        return $this->estado;
    }
}