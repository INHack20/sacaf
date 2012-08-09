<?php

namespace INHack20\MobiliarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use INHack20\MobiliarioBundle\Entity\Mobiliario;
use INHack20\MobiliarioBundle\Entity\Ubicacion;
use INHack20\MobiliarioBundle\Form\MobiliarioType;
use INHack20\MobiliarioBundle\Form\BuscarMobiliarioType;
use INHack20\InventarioBundle\Entity\Comprobante;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use MakerLabs\PagerBundle\Pager;
use MakerLabs\PagerBundle\Adapter\DoctrineOrmAdapter;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Mobiliario controller.
 *
 * @Route("/mobiliario")
 */
class MobiliarioController extends Controller
{
    /**
     * Lists all Mobiliario entities.
     *
     * @Route("/{comprobante_id}/{page}", name="mobiliario", requirements={"page" = "\d+","comprobante_id"="\d+"}, defaults={"page"="1","comprobante_id"="0"})
     * @Template()
     */
    public function indexAction($comprobante_id,$page)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $qb= $em->getRepository('INHack20MobiliarioBundle:Mobiliario')->createQueryBuilder('m')
                ->join('m.activo', 'a');
                
        $request= $this->getRequest();
        $menu = $request->query->get('menu');
        $accion = $request->query->get('accion');
        $estatus = $request->query->get('estatus');
        
        $form_buscar = $this->createForm(new BuscarMobiliarioType(),null,array('attr' => array('estado' => $this->container->get('security.context')->getToken()->getUser()->getEstado()->getId())));
        //si es una peticion Ajax
        if($request->isXmlHttpRequest())
            {
            $form_buscar->bindRequest($request);    
                if($form_buscar->isValid())
                {
                    $data=$form_buscar->getData();
                    $accion = $data['accion'];
                    //Valido si algun campo tiene datos de busqueda
                    if($data!="" || $data['fecha']!='' || $data['ubicacion']!="" || $data['nrobiennacional'!=''] || $data['descripcion']!='')
                    
                    if($data['fecha']!='')
                        $qb->andwhere($qb->expr()->like('a.creado', "'".$data['fecha']->format('Y-m-d')."%'"));
                        
                    if($data['ubicacion']!='')
                        $qb->andWhere('a.ubicacion = :ubicacion')
                                ->setParameter('ubicacion', $data['ubicacion']);
                    
                    if($data['nrobiennacional']!='')
                        $qb->andwhere($qb->expr()->like('a.nroBienNacional', "'".$data['nrobiennacional']."%'"));
                    
                    if($data['descripcion']!='')
                        $qb->andwhere($qb->expr()->like('m.descripcion', "'".$data['descripcion']."%'"));
                }
            }
			
		if($comprobante_id > 0){
			$comprobante= $em->getRepository ('INHack20InventarioBundle:Comprobante')->find($comprobante_id);
			if(!$comprobante){
				throw $this->createNotFoundException('Unable to find Comprobante entity.');
			}
				
			if($comprobante->getTipo() == $this->container->getParameter('COMPROBANTE_ENTREGA'))
			{
				$qb->andWhere('a.estatus = :estatus')->setParameter('estatus', $this->container->getParameter('STOCK_ALMACEN'));
			}
			elseif($comprobante->getTipo() == $this->container->getParameter('COMPROBANTE_REASIGNACION'))
			{
				$qb->andWhere('a.estatus != :estatus')->setParameter('estatus', $this->container->getParameter('STOCK_ALMACEN'));
				$qb->andWhere('a.estatus != :estatus')->setParameter('estatus', $this->container->getParameter('DESINCORPORADO'));
			}
			elseif($comprobante->getTipo() == $this->container->getParameter('COMPROBANTE_DESINCORPORACION'))
			{
				$qb->andWhere('a.estatus != :estatus')->setParameter('estatus', $this->container->getParameter('STOCK_ALMACEN'));
				$qb->andWhere('a.estatus != :estatus')->setParameter('estatus', $this->container->getParameter('DESINCORPORADO'));
			}
			else
			{
				throw $this->createNotFoundException('ERROR CON TIPO DE COMPROBANTE DESCONOCIDO.');
			}	
		}
		
        if($estatus!='')
                       $qb->andwhere($qb->expr()->like('a.estatus', "'".$estatus."%'"));
        $qb->orderBy('a.actualizado', 'DESC')
            ->setMaxResults($this->container->getParameter('LIMITE_PAGINACION'));
        
        //paginador
        $adapter= new DoctrineOrmAdapter($qb);
        $pager= new Pager(
                $adapter,
                array('page' => $page, 'limit' => $this->container->getParameter('LIMITE_PAGINACION'))
                );
         
            if($request->isXmlHttpRequest() && $menu == '')
            {
                if($comprobante_id > 0){
                    if($comprobante)
                        return $this->render('INHack20MobiliarioBundle:Mobiliario:lista.html.twig', 
                                array('pager'=> $pager,
                                      'comprobante_id'=>$comprobante_id,
                                      'comprobante' => $comprobante,
                                       'estatus' => $estatus,
                                        'accion' => $accion,
                                    ));
                    }
                else
                    return $this->render('INHack20MobiliarioBundle:Mobiliario:lista.html.twig', array(
                        'pager'=> $pager,
                        'comprobante_id' => $comprobante_id,
                        'estatus' => $estatus,
                        'accion' => $accion,
                        ));
            }
            
        return array(
            'pager' => $pager,
            'form_buscar' => $form_buscar->createView(),
            'comprobante_id' => $comprobante_id,
            'estatus' => $estatus,
            'accion' => $accion,
        );
    }
    
    /**
     * Finds and displays a Mobiliario entity.
     *
     * @Route("/{id}/show", name="mobiliario_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20MobiliarioBundle:Mobiliario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mobiliario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Mobiliario entity.
     *
     * @Route("/{orden_id}/new", name="mobiliario_new", requirements={"orden_id"="\d+"}, defaults={"orden_id"="0"})
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function newAction($orden_id)
    {
        $entity = new Mobiliario();
        $form = $this->createForm(new MobiliarioType(), $entity, array('attr' => array( 'estado' => $this->container->get('security.context')->getToken()->getUser()->getEstado()->getId())));
        
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'orden_id' => $orden_id,
        );
    }

    /**
     * Creates a new Mobiliario entity.
     *
     * @Route("/{orden_id}/create", name="mobiliario_create", requirements={"orden_id"="\d+"}, defaults={"orden_id"="0"})
     * @Method("post")
     * @Template("INHack20MobiliarioBundle:Mobiliario:new.html.twig")
     */
    public function createAction($orden_id)
    {
        $entity  = new Mobiliario();
        $request = $this->getRequest();
        $form    = $this->createForm(new MobiliarioType(), $entity,array('attr' => array( 'estado' => $this->container->get('security.context')->getToken()->getUser()->getEstado()->getId())));
        $form->bindRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            
            $activo=$entity->getActivo();
            $activo->setTipoActivo($this->container->getParameter('ACTIVO_MOBILIARIO'));
            if($activo->getUbicacion())
                    $activo->setEstatus($this->container->getParameter('ASIGNADO'));
                else
                    $activo->setEstatus($this->container->getParameter('STOCK_ALMACEN'));
            if($orden_id > 0){
                $orden = $em->getRepository('INHack20InventarioBundle:Orden')->find($orden_id);

                if (!$orden) {
                    throw $this->createNotFoundException('Unable to find Orden entity.');
                }
                $activo->setOrden($orden);
            }
            
            $em->persist($entity);
            $em->flush();
            if($request->isXmlHttpRequest()){
                if($orden_id > 0){
                    return $this->render('INHack20MobiliarioBundle:Mobiliario:lista_mobiliarios.html.twig', array('entities' => $orden->getActivos(),'orden_id' => $orden_id));
                }
                else
                    return $this->forward('INHack20MobiliarioBundle:Mobiliario:show', array('id' => $entity->getId()));
            }
            else
                return $this->redirect($this->generateUrl('mobiliario_show',array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'orden_id' => $orden_id,
        );
    }

    /**
     * Displays a form to edit an existing Mobiliario entity.
     *
     * @Route("/{orden_id}/{id}/edit", name="mobiliario_edit", requirements={"orden_id"="\d+"}, defaults={"orden_id"="0"})
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function editAction($orden_id,$id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20MobiliarioBundle:Mobiliario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mobiliario entity.');
        }

        $editForm = $this->createForm(new MobiliarioType(), $entity, array('attr' => array( 'estado' => $this->container->get('security.context')->getToken()->getUser()->getEstado()->getId())));
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'orden_id' => $orden_id,
        );
    }

    /**
     * Edits an existing Mobiliario entity.
     *
     * @Route("/{orden_id}/{id}/update", name="mobiliario_update", requirements={"orden_id"="\d+"}, defaults={"orden_id"="0"})
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("INHack20MobiliarioBundle:Mobiliario:edit.html.twig")
     */
    public function updateAction($orden_id,$id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();
        $entity = $em->getRepository('INHack20MobiliarioBundle:Mobiliario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mobiliario entity.');
        }

        $editForm   = $this->createForm(new MobiliarioType(), $entity, array('attr' => array( 'estado' => $this->container->get('security.context')->getToken()->getUser()->getEstado()->getId())));
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $activo = $entity->getActivo();
            if($activo->getUbicacion())
                    $activo->setEstatus($this->container->getParameter('ASIGNADO'));
                else
                    $activo->setEstatus($this->container->getParameter('STOCK_ALMACEN'));
            
            $em->persist($entity);
            $em->flush();
            
            if($request->isXmlHttpRequest()){
                if($orden_id > 0){
                    $orden = $em->getRepository('INHack20InventarioBundle:Orden')->find($orden_id);
                    if (!$orden) {
                        throw $this->createNotFoundException('Unable to find Orden entity.');
                    }
                    return $this->render('INHack20MobiliarioBundle:Mobiliario:lista_mobiliarios.html.twig', array('entities' => $orden->getActivos(),'orden_id' => $orden_id));
                }
                else
                    return $this->forward('INHack20MobiliarioBundle:Mobiliario:show', array('id' => $id));
            }
            else
                return $this->redirect($this->generateUrl('mobiliario_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Mobiliario entity.
     *
     * @Route("/{orden_id}/{id}/delete", name="mobiliario_delete", requirements={"orden_id"="\d+"}, defaults={"orden_id"="0"})
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction($orden_id,$id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('INHack20MobiliarioBundle:Mobiliario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Mobiliario entity.');
            }

            $em->remove($entity);
            $em->flush();
            
            if($orden_id > 0){
                $orden = $em->getRepository('INHack20InventarioBundle:Orden')->find($orden_id);
                if (!$orden) {
                    throw $this->createNotFoundException('Unable to find Orden entity.');
                }
                return $this->render('INHack20MobiliarioBundle:Mobiliario:lista_mobiliarios.html.twig', array('entities' => $orden->getActivos(),'orden_id' => $orden_id));
            }
            
        }
        if($request->isXmlHttpRequest())
            return $this->forward('INHack20MobiliarioBundle:Mobiliario:index');
        else
            return $this->redirect($this->generateUrl('mobiliario'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    /**
     * Lists all Mobiliario almacen.
     *
     * @Route("/consulta/{id}", name="mobiliario_ubicacion")
     * @ParamConverter("ubicacion",class="INHack20MobiliarioBundle:Ubicacion")
     * @Template()
     */
    public function ubicacionAction($ubicacion)
    {
        
        $em = $this->getDoctrine()->getEntityManager();
               
        $entities = $em->getRepository('INHack20MobiliarioBundle:Mobiliario')->findBy(array('ubicacion' => $ubicacion));

        return array(
            'entities' => $entities,
            'ubicacion' => $ubicacion,
        );
    }
    
    /**
     * Buscar un mobiliario por nro_inventario o descripcion 
     * 
     * @Route("/{id}/buscar/{page}", name="mobiliario_buscar", defaults={ "page" = "1" })
     * @ParamConverter("comprobante", class="INHack20InventarioBundle:Comprobante")
     * @Template()
     */
    public function buscarAction($page,Comprobante $comprobante)
    {
       
        $request = $this->getRequest();
        $busqueda= $request->query->get('busqueda');
        $tipo= $request->query->get('tipo');
        if($busqueda != NULL && $tipo != NULL)
        {
            /*
            if($tipo==0)
                $tipo='nro_inventario';
            if($tipo==1)
                $tipo='descripcion';
            */
            $em= $this->getDoctrine()->getEntityManager();
            
            //$entities = $em->getRepository('INHack20MobiliarioBundle:Mobiliario')->findByLike(
              //      $data['tipo'],$data['busqueda']);
            $tipo='m.'.$tipo;
            $busqueda='\'%'.$busqueda.'%\'';
            $qb = $em->createQueryBuilder();
               $qb->select('m')
                  ->from('INHack20MobiliarioBundle:Mobiliario','m')
                  ->where($qb->expr()->like($tipo, $busqueda))
                  ->orderBy($tipo,'DESC')  
                                        
                    ;
            //Paginar
            $adapter= new DoctrineOrmAdapter($qb);
            $pager= new Pager(
                        $adapter,
                        array('page' => $page, 'limit' => 10));
            
            return array(
                'pager' => $pager,
                'comprobante' => $comprobante,
                'busqueda' => $request->query->get('busqueda'),
                'tipo' => $request->query->get('tipo'),
                );
        }
        else
          throw $this->createNotFoundException ("Debe llenar todos los campos");
        
    }
}
