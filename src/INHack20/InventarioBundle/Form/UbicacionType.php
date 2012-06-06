<?php

namespace INHack20\InventarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UbicacionType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('dependencia',null,array('attr' => array('cols' => '40', 'rows' => '6')))
            ->add('estado','entity',array(
                'class' => 'INHack20\InventarioBundle\Entity\Estado',
                'property' => 'descripcion',
                'empty_value' => 'Seleccione',
            ))
        ;
    }

    public function getName()
    {
        return 'inhack20_InventarioBundle_ubicaciontype';
    }
}
