<?php

namespace INHack20\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Usuario")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    protected $nombre;

    /**
     * @ORM\Column(name="apellido", type="string", length=100, nullable=true)
     */
    protected $apellido;

    /**
     * @ORM\Column(name="cedula", type="string", length=40, nullable=true)
     */
    protected $cedula;

    /**
     * @ORM\Column(name="cargo", type="string", length=255, nullable=true)
     */
    protected $cargo;
    
    /**
     * @ORM\column(name="unidadAdministrativa", type="string", length=150, nullable=true)
     */
    private $unidadAdministrativa;
    
    /**
     * @ORM\ManyToOne(targetEntity="INHack20\InventarioBundle\Entity\Estado")
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id")
     */
    protected $estado;
    
    /**
     * @ORM\ManyToOne(targetEntity="INHack20\InventarioBundle\Entity\Firma")
     * @ORM\JoinColumn(name="firmaDirector_id")
     */
    private $firmaDirector;
    
    /**
     * @ORM\ManyToOne(targetEntity="INHack20\InventarioBundle\Entity\Firma")
     * @ORM\JoinColumn(name="firmaDivision_id")
     */
    private $firmaDivision;
    
    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * Set nombre
     *
     * @param string $nombre
     * @return User
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
     * Set apellido
     *
     * @param string $apellido
     * @return User
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
     * Set cedula
     *
     * @param string $cedula
     * @return User
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
     * Set cargo
     *
     * @param string $cargo
     * @return User
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
     * Set estado
     *
     * @param INHack20\InventarioBundle\Entity\Estado $estado
     * @return User
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

    /**
     * Set unidadAdministrativa
     *
     * @param string $unidadAdministrativa
     * @return User
     */
    public function setUnidadAdministrativa($unidadAdministrativa)
    {
        $this->unidadAdministrativa = $unidadAdministrativa;
        return $this;
    }

    /**
     * Get unidadAdministrativa
     *
     * @return string 
     */
    public function getUnidadAdministrativa()
    {
        return $this->unidadAdministrativa;
    }

    /**
     * Set firmaDirector
     *
     * @param INHack20\InventarioBundle\Entity\Firma $firmaDirector
     * @return User
     */
    public function setFirmaDirector(\INHack20\InventarioBundle\Entity\Firma $firmaDirector = null)
    {
        $this->firmaDirector = $firmaDirector;
        return $this;
    }

    /**
     * Get firmaDirector
     *
     * @return INHack20\InventarioBundle\Entity\Firma 
     */
    public function getFirmaDirector()
    {
        return $this->firmaDirector;
    }

    /**
     * Set firmaDivision
     *
     * @param INHack20\InventarioBundle\Entity\Firma $firmaDivision
     * @return User
     */
    public function setFirmaDivision(\INHack20\InventarioBundle\Entity\Firma $firmaDivision = null)
    {
        $this->firmaDivision = $firmaDivision;
        return $this;
    }

    /**
     * Get firmaDivision
     *
     * @return INHack20\InventarioBundle\Entity\Firma 
     */
    public function getFirmaDivision()
    {
        return $this->firmaDivision;
    }
}