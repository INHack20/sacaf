<?php

namespace INHack20\EquipoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ComponenteType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('descripcion',null,array(
                'label' => 'Descripci&oacute;n'
            ))
            //->add('equipo')
            ->add('tipoComponente','entity',array(
                'label' => 'Tipo',
                'class' => 'INHack20\EquipoBundle\Entity\TipoComponente',
                'property' => 'descripcion',
                'empty_value' => 'Seleccione',
            ))
        ;
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'INHack20\EquipoBundle\Entity\Componente',
        );
    }
    
    public function getName()
    {
        return 'componente';
    }
}
