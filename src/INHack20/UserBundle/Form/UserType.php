<?php

namespace INHack20\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UserType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('nombre')
            ->add('apellido')
            ->add('cedula')
            ->add('cargo')
            ->add('unidadAdministrativa')
            ->add('estado','entity',array(
                'class' => 'INHack20\InventarioBundle\Entity\Estado',
                    'property' => 'descripcion',
                    'empty_value' => 'Seleccione',
            ))
            ->add('firmaDirector','entity',array(
                'label' => 'Firma del Director',
                    'class' => 'INHack20\InventarioBundle\Entity\Firma',
                    'property' => 'descripcion',
                    'empty_value' => 'Seleccione',
            ))
            ->add('firmaDivision','entity',array(
                'label' => 'Firma Jefe Division',
                    'class' => 'INHack20\InventarioBundle\Entity\Firma',
                    'property' => 'descripcion',
                    'empty_value' => 'Seleccione',
            ))
            ->add('roles')
            ->add('enabled',null,array(
                'required' => false,
            ))
            ->add('locked',null,array(
                'required' => false,
            ))
        ;
       
    }

    public function getName()
    {
        return 'inhack20_user_profile';
    }
}
