<?php

namespace INHack20\EquipoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EquipoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('marca')
            ->add('modelo')
            ->add('serial')
            ->add('tipoEquipo','entity',array(
                'label' => 'Tipo',
                'class' => 'INHack20\EquipoBundle\Entity\TipoEquipo',
                'property' => 'descripcion',
                'empty_value' => 'Seleccione',
            ))
            ->add('activo', new \INHack20\InventarioBundle\Form\ActivoType(),array('attr' => array('tipoActivo' => 'MOBILIARIO')))
            ->add('componentes','collection',array(
                'type' => new ComponenteType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ))
        ;
    }

     public function getDefaultOptions(){
        return array('data_class' => 'INHack20\EquipoBundle\Entity\Equipo');
    }
    
    public function getName()
    {
        return 'inhack20_equipobundle_equipotype';
    }
}
