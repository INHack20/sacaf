<?php

namespace INHack20\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use INHack20\InventarioBundle\Entity\Comprobante;
use INHack20\InventarioBundle\Form\ComprobanteType;
use INHack20\MobiliarioBundle\Form\BuscarMobiliarioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use INHack20\MobiliarioBundle\Entity\Mobiliario;
use INHack20\InventarioBundle\Entity\Historial;
use Symfony\Component\HttpFoundation\Response;
use MakerLabs\PagerBundle\Pager;
use MakerLabs\PagerBundle\Adapter\DoctrineOrmAdapter;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Comprobante controller.
 *
 * @Route("/comprobante")
 */
class ComprobanteController extends Controller
{
    /**
     * Lists all Comprobante entities.
     * accion=1|2|3 Operaciones|Consulta|Reportes
     * @Route("/{page}", name="comprobante",requirements={"page"="\d+"}, defaults={ "page"="1" })
     * @Template()
     */
    public function indexAction($page)
    {
        $request= $this->getRequest();
        $tipo = $request->query->get('tipo');
        $accion = $request->query->get('accion');
        $menu = $request->query->get('menu');
        $em = $this->getDoctrine()->getEntityManager();
        $qb= $em->getRepository('INHack20InventarioBundle:Comprobante')->createQueryBuilder('c')
                ->where('c.tipo = :t')
                ->setParameter('t', $tipo);
        
        $form_buscar = $this->createBusquedaForm();
        //si es una peticion Ajax
        if($request->isXmlHttpRequest())
            {
                $form_buscar->bindRequest($request);
                if($form_buscar->isValid())
                {
                    $data=$form_buscar->getData();
                    if($data!="" || $data['ubicacion']!="" || $data['fecha']!='')
                    $qb= $em->getRepository('INHack20InventarioBundle:Comprobante')->createQueryBuilder('c');
                        $qb->where('c.tipo = :tipo')
                                ->setParameter('tipo',$tipo)
                                ;
                        if($data['fecha']!='')
                            $qb->andwhere($qb->expr()->like('c.creado', "'".$data['fecha']->format('Y-m-d')."%'"));
                        
                          if($data['ubicacion']!='')
                             $qb->andWhere('c.ubicacion = :ubicacion')
                                ->setParameter('ubicacion', $data['ubicacion']);
                        
                       
                }
            }
        $qb->orderBy('c.actualizado', 'DESC')
                        ->setMaxResults($this->container->getParameter('LIMITE_PAGINACION'));
        //paginador
        $adapter= new DoctrineOrmAdapter($qb);
        $pager= new Pager(
                $adapter,
                array('page' => $page, 'limit' => $this->container->getParameter('LIMITE_PAGINACION'))
                );
        
        
        
        if($request->isXmlHttpRequest() && $menu == '')
            {
                return $this->render('INHack20InventarioBundle:Comprobante:lista.html.twig', array('pager'=> $pager, 'tipo' => $tipo,'accion' => $accion));
            }
            
        return array(
            'pager' => $pager,
            'tipo' => $tipo,
            'form_buscar' => $form_buscar->createView(),
            'accion' => $accion,
        );
    }
    
    private function createBusquedaForm()
    {
        $estado=$this->container->get('security.context')->getToken()->getUser()->getEstado();
        return $this->createFormBuilder()
             ->add('tipobusqueda','choice',array(
                 'label' => 'Tipo De Busqueda',
                 'choices' => array('1' => 'Fecha',
                                    '2' => 'Ubicacion',
                                    '3' => 'Todos',
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
                 'attr' => array('style' => 'width:400px;font-size:9px'),
                 'class' => 'INHack20\InventarioBundle\Entity\Ubicacion',
                 'property' => 'dependencia',
                 'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($estado){
                        return $er->createQueryBuilder('u')
                                ->where('u.estado = :estado')
                                ->setParameter('estado', $estado)
                                ->OrderBy('u.dependencia', 'ASC');
                 }
             ))
             ->getForm()
             ;
    }

    /**
     * Finds and displays a Comprobante entity.
     *
     * @Route("/{id}/show", name="comprobante_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $accion = $this->getRequest()->query->get('accion');
        $entity = $em->getRepository('INHack20InventarioBundle:Comprobante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comprobante entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        if($entity->getTipoActivo() == $this->container->getParameter('ACTIVO_MOBILIARIO'))
            $tipoForm = new BuscarMobiliarioType();
        elseif($entity->getTipoActivo() == $this->container->getParameter('ACTIVO_EQUIPO'))
            $tipoForm = new \INHack20\EquipoBundle\Form\BuscarEquipoType();
        else
            throw $this->createNotFoundException('Error tipo de activo requerido para la busqueda');
            
        $buscarForm = $this->createForm($tipoForm,null,array('attr' => array('estado' => $this->container->get('security.context')->getToken()->getUser()->getEstado()->getId())));
        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'form_buscar' => $buscarForm->createView(),
            'accion' => $accion,
        );
    }

    /**
     * Displays a form to create a new Comprobante entity.
     *
     * @Route("/new", name="comprobante_new")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function newAction()
    {
        $request = $this->getRequest();
        $tipo = $request->query->get('tipo');
        $entity = new Comprobante();
        $entity->setTipo($tipo);
        $form = $this->createForm(new ComprobanteType(), $entity, array('attr' => array( 'estado' => $this->container->get('security.context')->getToken()->getUser()->getEstado())));

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Comprobante entity.
     *
     * @Route("/create", name="comprobante_create")
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("INHack20InventarioBundle:Comprobante:new.html.twig")
     */
    public function createAction()
    {
        $usuario= $this->container->get('security.context')->getToken()->getUser();
        $entity  = new Comprobante();
        $request = $this->getRequest();
        $form    = $this->createForm(new ComprobanteType(), $entity, array('attr' => array( 'estado' => $usuario->getEstado())));
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity->setUsuario($usuario);
            $entity->setEstado($usuario->getEstado());
            $em->persist($entity);
            $em->flush();
            if($request->isXmlHttpRequest())
                return $this->forward('INHack20InventarioBundle:Comprobante:show', array('id' => $entity->getId()),array('accion' => $this->container->getParameter('OPERACIONES')));
            else
                return $this->redirect($this->generateUrl('comprobante_show', array('id' => $entity->getId(),'accion' => $this->container->getParameter('OPERACIONES'))));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Comprobante entity.
     *
     * @Route("/{id}/edit", name="comprobante_edit")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('INHack20InventarioBundle:Comprobante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comprobante entity.');
        }

        $editForm = $this->createForm(new ComprobanteType(), $entity, array('attr' => array( 'estado' => $this->container->get('security.context')->getToken()->getUser()->getEstado(),'disabled' => true)));
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Comprobante entity.
     *
     * @Route("/{id}/update", name="comprobante_update")
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("INHack20InventarioBundle:Comprobante:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20InventarioBundle:Comprobante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comprobante entity.');
        }

        $editForm   = $this->createForm(new ComprobanteType(), $entity, array('attr' => array( 'estado' => $this->container->get('security.context')->getToken()->getUser()->getEstado())));
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);
        
        if ($editForm->isValid()) {
            
            foreach($entity->getActivos() as $activo){
                $activo->setUbicacion($entity->getUbicacion());
                $em->persist($activo);
            }
                    
            $em->persist($entity);
            $em->flush();
            
            if($request->isXmlHttpRequest())
                return $this->forward('INHack20InventarioBundle:Comprobante:show', array('id' => $id), array('accion' => $this->container->getParameter('OPERACIONES')));
            else
                return $this->redirect($this->generateUrl('comprobante_show', array(
                    'id' => $id,
                    'accion' => $this->container->getParameter('OPERACIONES'))));
            
        }
        
        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Comprobante entity.
     *
     * @Route("/{id}/delete", name="comprobante_delete")
     * @Secure(roles="ROLE_ADMIN")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('INHack20InventarioBundle:Comprobante')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Comprobante entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
        if($request->isXmlHttpRequest())
            return $this->forward('INHack20InventarioBundle:Comprobante:index');
        else
            return $this->redirect($this->generateUrl('comprobante'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * Ver un reporte
     *
     * @Route("/{id}/reporte", name="comprobante_reporte")
     * @Secure(roles="ROLE_SUPER_USER")
     */
    public function reporteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $entity = $em->getRepository('INHack20InventarioBundle:Comprobante')->find($id);
        
        new \INHack20\InventarioBundle\Extras\ReportesPDF\Comprobante\ReporteComprobante($entity,$this->container);

        return new Response('', 200, array('Content-Type' => 'application/pdf'));
    }
    
    /**
     * Asociar un mobiliario al comprobante 
     * @Route("/{id_comprobante}/{id_activo}", name="comprobante_asociar_activo")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function asociarActivoAction($id_comprobante,$id_activo)
    {
        $request= $this->getRequest();
        $em= $this->getDoctrine()->getManager();
        $comprobante= $em->getRepository('INHack20InventarioBundle:Comprobante')->find($id_comprobante);
        $activo= $em->getRepository('INHack20InventarioBundle:Activo')->find($id_activo);
        
        
        if(!$comprobante)
            throw $this->createNotFoundException ("No se ha encontrado la entidad Comprobante");
        if(!$activo)
            throw $this->createNotFoundException ("No se ha encontrado la entidad Activo");
        
        
        $historial = new Historial();
        $historial->setActivo($activo);
        $historial->setUbicacion($activo->getUbicacion());
        $historial->setComprobanteTipo($comprobante->getTipo());
        $historial->setEstatusActivo($activo->getEstatus());
        
        //Asignar el estatus correspondiente al activo
        if($comprobante->getTipo()==$this->container->getParameter('COMPROBANTE_ENTREGA'))
        {
            $activo->setEstatus($this->container->getParameter('ASIGNADO'));
        }
        elseif($comprobante->getTipo()==$this->container->getParameter('COMPROBANTE_REASIGNACION'))
        {
            $activo->setEstatus($this->container->getParameter('REASIGNADO'));
        }
        elseif($comprobante->getTipo()==$this->container->getParameter('COMPROBANTE_DESINCORPORACION'))
        {
            $activo->setEstatus($this->container->getParameter('DESINCORPORADO'));
        }
        
        $activo->setUbicacion($comprobante->getUbicacion());
        $comprobante->addActivo($activo);
        
        $em->persist($historial);
        $em->persist($activo);
        
        $em->flush();
        if($request->isXmlHttpRequest()){
            if($comprobante->getTipoActivo()==$this->container->getParameter('ACTIVO_MOBILIARIO')){
                return $this->render('INHack20MobiliarioBundle:Mobiliario:lista_mobiliarios.html.twig',array(
                    'entities' => $comprobante->getActivos(),
                    'comprobante_id' => $comprobante->getId(),
                    'accion' => $this->container->getParameter('OPERACIONES'),
                ));
                
            }elseif($comprobante->getTipoActivo()==$this->container->getParameter('ACTIVO_EQUIPO')){
                return $this->render('INHack20EquipoBundle:Equipo:lista_equipos.html.twig',array(
                'entities' => $comprobante->getActivos(),
                'comprobante_id' => $comprobante->getId(),
                'accion' => $this->container->getParameter('OPERACIONES'),
            ));
            }
            else
                throw $this->createNotFoundException('ERROR EL TIPO DE ACTIVO '. $comprobante->getTipoActivo().' NO ES VALIDO');
        }
        
            return $this->redirect($this->generateUrl('comprobante_show', array('id' => $id_comprobante,'accion' => $this->container->getParameter('OPERACIONES'))));
    }
    
    /**
     * Remover un activo asociado a un comprobante
     * @Route("/{comprobante_id}/remove/{activo_id}", name="comprobante_remover_activo") 
     * @Secure(roles="ROLE_ADMIN")
     */
    public function removerActivo($comprobante_id,$activo_id)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $comprobante = $em->getRepository('INHack20InventarioBundle:Comprobante')->find($comprobante_id);
            if(!$comprobante){
                throw $this->createNotFoundException('No se ha encontrado la entidad Comprobante');
            }
        $activo = $em->getRepository('INHack20InventarioBundle:Activo')->find($activo_id);
            if(!$activo){
                throw $this->createNotFoundException('No se ha encontrado la entidad Comprobante');
            }
       $historial = $em->getRepository('INHack20InventarioBundle:Historial')->findLast($comprobante->getTipo(),$activo_id);
        
       $comprobante->getActivos()->removeElement($activo);
       //$comprobante_activos->removeElement($activo);
       
       if($historial){
           $activo->setUbicacion($historial->getUbicacion());
           $activo->setEstatus($historial->getEstatusActivo());
           
           $em->persist($activo);
       }
       
       $em->persist($comprobante);
       $em->flush();
       
       if($request->isXmlHttpRequest()){
            if($comprobante->getTipoActivo()==$this->container->getParameter('ACTIVO_MOBILIARIO')){
                return $this->render('INHack20MobiliarioBundle:Mobiliario:lista_mobiliarios.html.twig',array(
                    'entities' => $comprobante->getActivos(),
                    'comprobante_id' => $comprobante->getId(),
                ));
                
            }elseif($comprobante->getTipoActivo()==$this->container->getParameter('ACTIVO_EQUIPO')){
                return $this->render('INHack20EquipoBundle:Equipo:lista_equipos.html.twig',array(
                'entities' => $comprobante->getActivos(),
                'comprobante_id' => $comprobante->getId(),
            ));
            }
            else
                throw $this->createNotFoundException('ERROR EL TIPO DE ACTIVO '. $comprobante->getTipoActivo().' NO ES VALIDO');
        }
       
       return $this->redirect($this->generateUrl('comprobante_show',array('id' => $comprobante_id,'accion' => $this->container->getParameter('OPERACIONES'))));
    }
}