<?php

namespace INHack20\InventarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class OrdenType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('ordenCompra',null,array('label' => 'Orden de Compra'))
            ->add('fechaCompra',null,array('label' => 'Fecha de Compra',
                                            'widget' => 'single_text',
                                            'attr' => array('class' => 'fecha1'),
                                            ))
            ->add('empresa')
            ->add('factura')
            ->add('fechaFactura',null,array('label' => 'Fecha de Factura',
                                             'widget' => 'single_text',
                                             'attr' => array('class' => 'fecha2'),
                ))
            ->add('actaRecepcion',null,array('label' => 'Acta de Recepci&oacute;n'))
            ->add('actaRecepcionFecha',null,array('label' => 'Fecha del Acta de Recepci&oacute;n',
                                                    'widget' => 'single_text',
                                                    'attr' => array('class' => 'fecha3')
                ))
            ->add('tipoActivo','choice',array(
                'label' => 'Tipo de Activo',
                'choices' => array('1' => 'MOBILIARIO', '2' => 'EQUIPO'),
                'empty_value' => 'Seleccione',
                'attr' => array(isset($options['attr']['disabled'])? 'disabled' : false),
            ))
         ;
    }

    public function getName()
    {
        return 'inhack20_InventarioBundle_ordentype';
    }
}
