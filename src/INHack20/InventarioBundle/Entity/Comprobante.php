<?php

namespace INHack20\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * INHack20\InventarioBundle\Entity\Comprobante
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Comprobante
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
     * @ORM\ManyToOne(targetEntity="Ubicacion", inversedBy="comprobantes")
     * @ORM\JoinColumn(name="ubicacion_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $ubicacion;
    
    /**
     * @var integer $almacen_codigo
     *
     * @ORM\Column(name="almacen_codigo", type="integer", nullable=true)
     */
    private $almacen_codigo;

    /**
     * @var text $almacen_denominacion
     *
     * @ORM\Column(name="almacen_denominacion", type="text", nullable=true)
     */
    private $almacen_denominacion;

    /**
     * @var string $almacen_resp_ci
     *
     * @ORM\Column(name="almacen_resp_ci", type="string", length=30, nullable=true)
     */
    private $almacen_resp_ci;

    /**
     * @var string $almacen_resp_apell_nom
     *
     * @ORM\Column(name="almacen_resp_apell_nom", type="string", length=255, nullable=true)
     */
    private $almacen_resp_apell_nom;

    /**
     * @var text $nota
     *
     * @ORM\Column(name="nota", type="text", nullable=true)
     */
    private $nota;

    /**
     * @ORM\Column(name="tipo", type="integer")
     */
    private $tipo;
    
    /**
     * @ORM\Column(name="tipoactivo", type="integer")
     * @Assert\NotBlank()
     */
    protected $tipoactivo;

    /**
     * @ORM\Column(name="creado", type="datetime")
     */
    protected $creado;
    
    /**
     * @ORM\Column(name="actualizado", type="datetime", nullable=true)
     */
    protected $actualizado;

    /**
     * @ORM\ManyToOne(targetEntity="Estado")
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id")
     */
    private $estado;
    
    /**
     * @ORM\ManyToOne(targetEntity="INHack20\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $usuario;
    
    /**
     * @ORM\ManyToMany(targetEntity="Activo", inversedBy="comprobantes")
     * @ORM\JoinTable(name="comprobantes_activos",
     *      joinColumns={@ORM\JoinColumn(name="comprobante_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="activo_id", referencedColumnName="id")}        
     *      )
     */
    protected $activos;
        
    public function __construct()
    {
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
     * Set almacen_codigo
     *
     * @param integer $almacenCodigo
     * @return Comprobante
     */
    public function setAlmacenCodigo($almacenCodigo)
    {
        $this->almacen_codigo = $almacenCodigo;
        return $this;
    }

    /**
     * Get almacen_codigo
     *
     * @return integer 
     */
    public function getAlmacenCodigo()
    {
        return $this->almacen_codigo;
    }

    /**
     * Set almacen_denominacion
     *
     * @param text $almacenDenominacion
     * @return Comprobante
     */
    public function setAlmacenDenominacion($almacenDenominacion)
    {
        $this->almacen_denominacion = $almacenDenominacion;
        return $this;
    }

    /**
     * Get almacen_denominacion
     *
     * @return text 
     */
    public function getAlmacenDenominacion()
    {
        return $this->almacen_denominacion;
    }

    /**
     * Set almacen_resp_ci
     *
     * @param string $almacenRespCi
     * @return Comprobante
     */
    public function setAlmacenRespCi($almacenRespCi)
    {
        $this->almacen_resp_ci = $almacenRespCi;
        return $this;
    }

    /**
     * Get almacen_resp_ci
     *
     * @return string 
     */
    public function getAlmacenRespCi()
    {
        return $this->almacen_resp_ci;
    }

    /**
     * Set almacen_resp_apell_nom
     *
     * @param string $almacenRespApellNom
     * @return Comprobante
     */
    public function setAlmacenRespApellNom($almacenRespApellNom)
    {
        $this->almacen_resp_apell_nom = $almacenRespApellNom;
        return $this;
    }

    /**
     * Get almacen_resp_apell_nom
     *
     * @return string 
     */
    public function getAlmacenRespApellNom()
    {
        return $this->almacen_resp_apell_nom;
    }

    /**
     * Set nota
     *
     * @param text $nota
     * @return Comprobante
     */
    public function setNota($nota)
    {
        $this->nota = $nota;
        return $this;
    }

    /**
     * Get nota
     *
     * @return text 
     */
    public function getNota()
    {
        return $this->nota;
    }

    /**
     * Set creado
     *
     * @param datetime $creado
     * @return Comprobante
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
     * @return Comprobante
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
     * Set ubicacion
     *
     * @param INHack20\InventarioBundle\Entity\Ubicacion $ubicacion
     * @return Comprobante
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
     * Set usuario
     *
     * @param INHack20\UserBundle\Entity\User $usuario
     * @return Comprobante
     */
    public function setUsuario(\INHack20\UserBundle\Entity\User $usuario = null)
    {
        $this->usuario = $usuario;
        return $this;
    }

    /**
     * Get usuario
     *
     * @return INHack20\UserBundle\Entity\User 
     */
    public function getUsuario()
    {
        return $this->usuario;
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
     * Set tipo
     *
     * @param integer $tipo
     * @return Comprobante
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set tipoactivo
     *
     * @param integer $tipoactivo
     * @return Comprobante
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

    /**
     * Set estado
     *
     * @param INHack20\InventarioBundle\Entity\Estado $estado
     * @return Comprobante
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