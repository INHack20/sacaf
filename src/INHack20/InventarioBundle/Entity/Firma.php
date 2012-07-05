<?php

namespace INHack20\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * INHack20\InventarioBundle\Entity\Firma
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Firma
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
     * @ORM\Column(name="cedula", type="string", length=30, nullable=true)
     */
    private $cedula;
    
    /**
     * @var string $apellido
     *
     * @ORM\Column(name="apellido", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $apellido;
    
    /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var string $cargo
     *
     * @ORM\Column(name="cargo", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $cargo;

    /**
     * @ORM\ManyToOne(targetEntity="Ubicacion", inversedBy="firmas")
     * @ORM\JoinColumn(name="ubicacion_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    protected $ubicacion;
    
    /**
     * @ORM\ManyToOne(targetEntity="Estado")
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id")
     */
    protected $estado;
        
    /**
     * @ORM\Column(name="creado", type="datetime")
     */
    protected $creado;
    
    /**
     * @ORM\Column(name="actualizado", type="datetime", nullable=true)
     */
    protected $actualizado;
    

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
     * Set cedula
     *
     * @param string $cedula
     * @return Firma
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;
        return $this;
    }

    /**
     * Get cedula
     *
     * @return string 
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     * @return Firma
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Firma
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set cargo
     *
     * @param string $cargo
     * @return Firma
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;
        return $this;
    }

    /**
     * Get cargo
     *
     * @return string 
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set creado
     *
     * @param datetime $creado
     * @return Firma
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
     * @return Firma
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
     * @return Firma
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
     * Set estado
     *
     * @param INHack20\InventarioBundle\Entity\Estado $estado
     * @return Firma
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
    
    public function getNombreCompleto(){
        return $this->nombre . ' ' . $this->apellido;
    }
    
    public function getDescripcion(){
        return $this->getNombreCompleto().'('.$this->cargo.')';
    }
}