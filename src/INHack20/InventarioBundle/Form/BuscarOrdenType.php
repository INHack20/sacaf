<?php

namespace INHack20\InventarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Description of BuscarOrdenType
 *
 * @author INHACK20
 */
class BuscarOrdenType extends AbstractType{
     public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('tipobusqueda','choice',array(
                 'label' => 'Tipo De Busqueda',
                 'choices' => array(
                     '1' => 'Fecha de Registro',
                     '2' => 'Orden de Compra',
                     '3' => 'Fecha de Compra',
                     '4' => 'Empresa',
                     '5' => 'Factura',
                     '6' => 'Fecha de Factura',
                     '7' => 'Acta de Recepcion',
                     '8' => 'Acta de Recepción Fecha',
                     '9' => 'Tipo de Activo',
                     '10' => 'Todos',
                     ),
                 'empty_value' => 'Seleccione',
             ))
             ->add('fecha','date',array(
                 'input' => 'datetime',
                 'widget' => 'single_text',
                 'required' => false,
                 
             ))
             ->add('ordenCompra',null,array(
                 'label' => 'Orden de Compra',
                 'required' => false,
            ))
            ->add('fechaCompra','date',array(
                'label' => 'Fecha de Compra',
                'widget' => 'single_text',
                'attr' => array('class' => 'fecha1'),
                'required' => false,
            ))
            ->add('empresa',null,array(
                 'required' => false,
             ))
            ->add('factura',null,array(
                 'required' => false,
            ))
            ->add('fechaFactura','date',array(
                'label' => 'Fecha de Factura',
                'widget' => 'single_text',
                'attr' => array('class' => 'fecha2'),
                'required' => false,
                ))
            ->add('actaRecepcion',null,array(
                'label' => 'Acta de Recepci&oacute;n',
                'required' => false,
            ))
            ->add('actaRecepcionFecha','date',array(
                'label' => 'Fecha del Acta de Recepci&oacute;n',
                'widget' => 'single_text',
                'attr' => array('class' => 'fecha3'),
                'required' => false,
                ))
            ->add('tipoActivo','choice',array(
                'label' => 'Tipo de Activo',
                'choices' => array('1' => 'MOBILIARIO', '2' => 'EQUIPO'),
                'empty_value' => 'Seleccione',
                'required' => false,
            ))
             
         ;
    }

    public function getName()
    {
        return 'form_buscar';
    }
}

?>