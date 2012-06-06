<?php

namespace INHack20\MobiliarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * INHack20\MobiliarioBundle\Entity\Mobiliario
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="INHack20\MobiliarioBundle\Repository\MobiliarioRepository")
  */
class Mobiliario
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
     * @var string $codigo_catalogo
     *
     * @ORM\Column(name="codigo_catalogo", type="string", length=50)
     */
    private $codigo_catalogo;

    /**
     * @var integer $unidad_tributaria
     *
     * @ORM\Column(name="unidad_tributaria", type="integer", nullable=true)
     */
    private $unidad_tributaria;

    /**
     * @var text $descripcion
     *
     * @ORM\Column(name="descripcion", type="text")
     * @Assert\NotBlank()
     */
    private $descripcion;

    /**
     * @var decimal $valor
     *
     * @ORM\Column(name="valor", type="decimal",scale=2)
     * @Assert\NotBlank()
     * @Assert\Type(type="real", message="El valor {{ value }} debe ser tipo {{ type }}.")
     */
    private $valor;
    
    //Activo
    
    /**
     * @ORM\OneToOne(targetEntity="INHack20\InventarioBundle\Entity\Activo", inversedBy="mobiliario", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="activo_id", referencedColumnName="id")
     */
    protected $activo;

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
     * Set codigo_catalogo
     *
     * @param string $codigoCatalogo
     * @return Mobiliario
     */
    public function setCodigoCatalogo($codigoCatalogo)
    {
        $this->codigo_catalogo = $codigoCatalogo;
        return $this;
    }

    /**
     * Get codigo_catalogo
     *
     * @return string 
     */
    public function getCodigoCatalogo()
    {
        return $this->codigo_catalogo;
    }

    /**
     * Set unidad_tributaria
     *
     * @param integer $unidadTributaria
     * @return Mobiliario
     */
    public function setUnidadTributaria($unidadTributaria)
    {
        $this->unidad_tributaria = $unidadTributaria;
        return $this;
    }

    /**
     * Get unidad_tributaria
     *
     * @return integer 
     */
    public function getUnidadTributaria()
    {
        return $this->unidad_tributaria;
    }

    /**
     * Set descripcion
     *
     * @param text $descripcion
     * @return Mobiliario
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return text 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set valor
     *
     * @param decimal $valor
     * @return Mobiliario
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Get valor
     *
     * @return decimal 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set activo
     *
     * @param INHack20\InventarioBundle\Entity\Activo $activo
     * @return Mobiliario
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
}