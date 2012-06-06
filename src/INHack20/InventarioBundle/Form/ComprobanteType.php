<?php

namespace INHack20\InventarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityRepository;

class ComprobanteType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('ubicacion','entity',array('label' => 'Ubicaci&oacute;n',
                    'class' => 'INHack20\InventarioBundle\Entity\Ubicacion',
                    'property' => 'dependencia',
                    'attr' => array('style' => 'width:400px;font-size:9px'),
                    'empty_value' => 'Seleccione',
                    //'disabled' => isset($options['attr']['disabled'])?$options['attr']['disabled'] : false,
                    'query_builder' => function(EntityRepository $er) use ($options){
                        return $er->createQueryBuilder('u')
                                ->where('u.estado = :estado')
                                ->setParameter('estado', $options['attr']['estado'])
                                ->OrderBy('u.dependencia', 'ASC');
                    },
             ))
            ->add('almacen_codigo',null,array('label' => 'Codigo',))
            ->add('almacen_denominacion',null,array('label' => 'Denominaci&oacute;n', 'attr' => array('cols' => '40'),))
            ->add('almacen_resp_ci',null,array('label' => 'Cedula del Responsable',))
            ->add('almacen_resp_apell_nom',null,array('label' => 'Nombre del Responsable','attr' => array('size' => '40'),))
            ->add('tipoactivo','choice',array(
                'label' => 'Tipo de Activo',
                'choices' => array('1' => 'MOBILIARIO', '2' => 'EQUIPO'),
                'empty_value' => 'Seleccione',
            ))
            ->add('nota',null,array('attr' => array('cols' => '40','rows' => '6'),))
            ->add('tipo','hidden')
            
          ;
    }

    public function getName()
    {
        return 'inhack20_InventarioBundle_comprobantetype';
    }
}
