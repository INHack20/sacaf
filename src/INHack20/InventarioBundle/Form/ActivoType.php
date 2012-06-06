<?php

namespace INHack20\InventarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ActivoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nroBienNacional',null,array(
                'label' => 'N&deg; Bien Nacional',
            ))
            ->add('observacion',null,array(
                'label' => 'Observaci&oacute;n',
                'attr' => array('rows' => '5', 'cols' => '30'),
                ))
            //->add('orden')
            //->add('estatus')
            //->add('estado')
            //->add('mobiliario')
            //->add('equipo')
        ;
    }
    
     public function getDefaultOptions()
    {
        return array(
            'data_class' => 'INHack20\InventarioBundle\Entity\Activo',
        );
    }

    public function getName()
    {
        return 'inhack20_inventariobundle_activotype';
    }
}
