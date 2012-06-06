<?php

namespace INHack20\EquipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * INHack20\EquipoBundle\Entity\Componente
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Componente
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
     * @Assert\NotBlank()
     */
    private $descripcion;
    
    /**
     * @ORM\ManyToOne(targetEntity="Equipo", inversedBy="componentes")
     * @ORM\JoinColumn(name="equipo_id", referencedColumnName="id")
     */
    protected $equipo;
    
    /**
     * @ORM\ManyToOne(targetEntity="TipoComponente", inversedBy="componentes")
     * @ORM\JoinColumn(name="tipocomponente_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    protected $tipocomponente;
    
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Componente
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
     * Set equipo
     *
     * @param INHack20\EquipoBundle\Entity\Equipo $equipo
     * @return Componente
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
     * Set tipoComponente
     *
     * @param INHack20\EquipoBundle\Entity\TipoComponente $tipoComponente
     * @return Componente
     */
    public function setTipoComponente(\INHack20\EquipoBundle\Entity\TipoComponente $tipocomponente = null)
    {
        $this->tipocomponente = $tipocomponente;
        return $this;
    }

    /**
     * Get tipoComponente
     *
     * @return INHack20\EquipoBundle\Entity\TipoComponente 
     */
    public function getTipoComponente()
    {
        return $this->tipocomponente;
    }

    /**
     * Set creado
     *
     * @param datetime $creado
     * @return Componente
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
     * @return Componente
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
    /*
    public function setEquipo(Equipo $equipo)
    {
        if (!$this->equipo->contains($equipo)) {
            $this->equipo->add($equipo);
        }
    }*/
}