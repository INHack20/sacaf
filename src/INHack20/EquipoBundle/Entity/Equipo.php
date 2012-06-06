<?php

namespace INHack20\EquipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * INHack20\EquipoBundle\Entity\Equipo
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Equipo
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
     * @var string $marca
     *
     * @ORM\Column(name="marca", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $marca;

    /**
     * @var string $modelo
     *
     * @ORM\Column(name="modelo", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $modelo;

    /**
     * @var string $serial
     *
     * @ORM\Column(name="serial", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $serial;

    /**
     * @ORM\ManyToOne(targetEntity="TipoEquipo", inversedBy="equipos")
     * @ORM\JoinColumn(name="tipoequipo_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    protected $tipoEquipo;
    
    /**
     * @ORM\OneToMany(targetEntity="Componente", mappedBy="equipo", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $componentes;

    //Activo
    
    /**
     * @ORM\OneToOne(targetEntity="INHack20\InventarioBundle\Entity\Activo", inversedBy="equipo", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="activo_id", referencedColumnName="id")
     * @Assert\Type(type="INHack20\InventarioBundle\Entity\Activo")
     */
    protected $activo;
    
    public function __construct()
    {
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
     * Set marca
     *
     * @param string $marca
     * @return Equipo
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;
        return $this;
    }

    /**
     * Get marca
     *
     * @return string 
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * Set modelo
     *
     * @param string $modelo
     * @return Equipo
     */
    public function setModelo($modelo)
    {
        $this->modelo = $modelo;
        return $this;
    }

    /**
     * Get modelo
     *
     * @return string 
     */
    public function getModelo()
    {
        return $this->modelo;
    }

    /**
     * Set serial
     *
     * @param string $serial
     * @return Equipo
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;
        return $this;
    }

    /**
     * Get serial
     *
     * @return string 
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Set tipoEquipo
     *
     * @param INHack20\EquipoBundle\Entity\TipoEquipo $tipoEquipo
     * @return Equipo
     */
    public function setTipoEquipo(\INHack20\EquipoBundle\Entity\TipoEquipo $tipoEquipo = null)
    {
        $this->tipoEquipo = $tipoEquipo;
        return $this;
    }

    /**
     * Get tipoEquipo
     *
     * @return INHack20\EquipoBundle\Entity\TipoEquipo 
     */
    public function getTipoEquipo()
    {
        return $this->tipoEquipo;
    }

    /**
     * Add componentes
     *
     * @param INHack20\EquipoBundle\Entity\Componente $componentes
     */
    public function addComponente(\INHack20\EquipoBundle\Entity\Componente $componentes)
    {
        $componentes->setEquipo($this);
        $this->componentes[] = $componentes;
    }
    
    /**
     * Add componentes
     *
     * @param INHack20\EquipoBundle\Entity\Componente $componentes
     */
    public function removeComponente(\INHack20\EquipoBundle\Entity\Componente $componentes)
    {
        //$this->componentes->remove($componentes);
        $this->componentes->removeElement($componentes);
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

    /**
     * Set activo
     *
     * @param INHack20\InventarioBundle\Entity\Activo $activo
     * @return Equipo
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
    
    public function setComponentes(ArrayCollection $componentes)
    {
        foreach ($componentes as $componente) {
            $componente->setEquipo($this);
        }
        $this->componentes = $componentes;
    }
}