<?php

namespace INHack20\MobiliarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityRepository;

class MobiliarioType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('codigo_catalogo',null,array('label' => 'C&oacute;digo del catalogo',
                                                'required' => true,
                ))
            //->add('nro_inventario',null,array('label' => 'N&deg; Inventario'))
            ->add('unidad_tributaria',null,array('label' => 'U.T.',
                                                 'required' => false,
                ))
            ->add('descripcion',null,array('label' => 'Descripci&oacute;n','attr' => array('cols' => '40', 'rows' => '5')))
            ->add('valor')
            //->add('orden','entity',array('class' => 'INHack20\MobiliarioBundle\Entity\Orden', 'property' => 'id',))
             ->add('activo', new \INHack20\InventarioBundle\Form\ActivoType(),array('attr' => array('tipoActivo' => 'EQUIPO')))
        ;
    }

    public function getName()
    {
        return 'inhack20_mobiliariobundle_mobiliariotype';
    }
}
