<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace INHack20\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder->add('nombre')
                ->add('apellido')
                ->add('cedula')
                ->add('cargo')
                ->add('unidadAdministrativa',null,array(
                    'label' => 'Unidad Administrativa',
                ))
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
                ;
    }

    public function getName()
    {
        return 'inhack20_user_registration';
    }
}