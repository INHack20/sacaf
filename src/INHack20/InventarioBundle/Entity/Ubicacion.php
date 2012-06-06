<?php

namespace INHack20\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * INHack20\InventarioBundle\Entity\Ubicacion
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Ubicacion
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
     * @var integer
     * @ORM\Column(name="codigo", type="integer", nullable=true)
     */
    private $codigo;
    
    /**
     * @var text $dependencia
     *
     * @ORM\Column(name="dependencia", type="text")
     * @Assert\NotBlank()
     */
    private $dependencia;

    /**
     * @ORM\oneToMany(targetEntity="Firma", mappedBy="ubicacion")
     */
    protected $firmas;

    /**
     * @ORM\OneToMany(targetEntity="Comprobante", mappedBy="ubicacion")
     */
    protected $comprobantes;
    
    /**
     * @ORM\Column(name="creado", type="datetime")
     */
    protected $creado;
    
    /**
     * @ORM\Column(name="actualizado", type="datetime", nullable=true)
     */
    protected $actualizado;
    
    /**
     * @ORM\ManyToOne(targetEntity="Estado", inversedBy="ubicaciones")
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Seleccione un estado")
     */
    protected $estado;
    
    /**
     * @ORM\OneToMany(targetEntity="Activo", mappedBy="ubicacion")
     */
    private $activos;
    
    public function __construct()
    {
        $this->firmas= new ArrayCollection();
        $this->comprobantes= new ArrayCollection();
        $this->activos= new ArrayCollection();
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
     * Set codigo
     *
     * @param integer $codigo
     * @return Ubicacion
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * Get codigo
     *
     * @return integer 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set dependencia
     *
     * @param text $dependencia
     * @return Ubicacion
     */
    public function setDependencia($dependencia)
    {
        $this->dependencia = $dependencia;
        return $this;
    }

    /**
     * Get dependencia
     *
     * @return text 
     */
    public function getDependencia()
    {
        return $this->dependencia;
    }

    /**
     * Set creado
     *
     * @param datetime $creado
     * @return Ubicacion
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
     * @return Ubicacion
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
     * Add firmas
     *
     * @param INHack20\InventarioBundle\Entity\Firma $firmas
     */
    public function addFirma(\INHack20\InventarioBundle\Entity\Firma $firmas)
    {
        $this->firmas[] = $firmas;
    }

    /**
     * Get firmas
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFirmas()
    {
        return $this->firmas;
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
     * Set estado
     *
     * @param INHack20\InventarioBundle\Entity\Estado $estado
     * @return Ubicacion
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
}