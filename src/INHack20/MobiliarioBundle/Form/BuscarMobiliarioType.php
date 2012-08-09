<?php

namespace INHack20\MobiliarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Description of BuscarMobiliarioType
 *
 * @author INHACK20
 */
class BuscarMobiliarioType  extends AbstractType{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('tipobusqueda','choice',array(
                 'label' => 'Tipo De Busqueda',
                 'choices' => array('1' => 'Fecha',
                                    '2' => 'Ubicacion', 
                                    '3' => 'Nro Bien Nacional', 
                                    '4' => 'Descripcion',
                                    '5' => 'Todos'
                     ),
                 'empty_value' => 'Seleccione',
             ))
             ->add('fecha','date',array(
                 'input' => 'datetime',
                 'widget' => 'single_text',
                 'required' => false,
                 
             ))
             ->add('ubicacion','entity',array(
                 'required' => false,
                 'label' => 'Ubicaci&oacute;n',
                 'empty_value' => 'Seleccione',
                 'class' => 'INHack20\InventarioBundle\Entity\Ubicacion',
                 'property' => 'dependencia',
                 'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($options){
                        return $er->createQueryBuilder('u')
                                ->where('u.estado = :estado')
                                ->setParameter('estado', $options['attr']['estado'])
                                ->OrderBy('u.dependencia', 'ASC');
                 }
             ))
             ->add('nrobiennacional',null,array(
                 'label' => 'N&deg; Bien Nacional',
                 'required' => false,
             ))
             ->add('descripcion','textarea',array(
                 'attr' => array('rows' => '3', 'cols' => '50'),
                 'required' => false,
             ))
             ->add('accion','hidden',array(
                 'data' => '1'
             ))
         ;
    }

    public function getName()
    {
        return 'form_buscar';
    }    
}