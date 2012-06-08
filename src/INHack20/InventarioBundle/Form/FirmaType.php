<?php

namespace INHack20\InventarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityRepository;

class FirmaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        
        $builder
            ->add('cedula',null,array('label' => 'Cedula',
                                        'attr' => array('size' => '50'),
                ))
            ->add('nombre',null,array('label' => 'Nombre',
                                        'attr' => array('size' => '50'),
                ))
            ->add('apellido',null,array('label' => 'Apellido',
                                        'attr' => array('size' => '50'),
                ))
            ->add('cargo',null,array('attr' => array('size' => '50'),))
            ->add('ubicacion','entity',array('label' => 'Ubicaci&oacute;n',
                'class' => 'INHack20\InventarioBundle\Entity\Ubicacion',
                'property' => 'dependencia',
                //'attr' => array('style' => 'width:500px;font-size:9px'),
                'attr' => array(isset($options['attr']['disabled'])? 'disabled' : false),
                'empty_value' => 'Seleccione',
                'query_builder' => function(EntityRepository $er) use ($options){
                        return $er->createQueryBuilder('u')
                                ->where('u.estado = :estado')
                                ->setParameter('estado', $options['attr']['estado'])
                                ->OrderBy('u.dependencia', 'ASC');
                 },
                ))
        ;
    }

    public function getName()
    {
        return 'inhack20_InventarioBundle_firmatype';
    }
}
