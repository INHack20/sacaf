<?php

namespace INHack20\EquipoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Description of BuscarEquipoType
 *
 * @author INHACK20
 */
class BuscarEquipoType extends AbstractType{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('tipobusqueda','choice',array(
                 'label' => 'Tipo De Busqueda',
                 'choices' => array('1' => 'Fecha',
                                    '2' => 'Ubicacion', 
                                    '3' => 'Nro Bien Nacional', 
                                    '4' => 'Marca',
                                    '5' => 'Modelo',
                                    '6' => 'Serial',
                                    '7' => 'Todos',
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
             ->add('marca','entity',array(
                 'empty_value' => 'Seleccione',
                 'class' => 'INHack20\EquipoBundle\Entity\Equipo',
                 'property' => 'marca',
                 'query_builder' => function(\Doctrine\ORM\EntityRepository $er){
                        return $er->createQueryBuilder('e')
                                ->groupBy('e.marca')
                                ->OrderBy('e.marca', 'ASC');
                 },
                 'required' => false,
             ))
             ->add('modelo',null,array(
                 'required' => false,
             ))
             ->add('serial',null,array(
                 'required' => false,
             ))
         ;
    }

    public function getName()
    {
        return 'form_buscar';
    }    
}