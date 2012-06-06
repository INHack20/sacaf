<?php

namespace INHack20\EquipoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TipoEquipoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('descripcion')
        ;
    }

    public function getName()
    {
        return 'inhack20_equipobundle_tipoequipotype';
    }
}
