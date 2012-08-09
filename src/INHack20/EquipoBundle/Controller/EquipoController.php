<?php

namespace INHack20\EquipoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use INHack20\EquipoBundle\Entity\Equipo;
use INHack20\EquipoBundle\Entity\Componente;
use INHack20\EquipoBundle\Form\EquipoType;
use MakerLabs\PagerBundle\Pager;
use MakerLabs\PagerBundle\Adapter\DoctrineOrmAdapter;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Equipo controller.
 *
 * @Route("/equipo")
 */
class EquipoController extends Controller
{
    /**
     * Lists all Equipo entities.
     *
     * @Route("/{comprobante_id}/{page}", name="equipo", requirements={"page"="\d+","comprobante_id"="\d+"}, defaults={"page"="1","comprobante_id"="0"})
     * @Template()
     */
    public function indexAction($comprobante_id,$page)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $qb= $em->getRepository('INHack20EquipoBundle:Equipo')->createQueryBuilder('e')
                ->join('e.activo', 'a');
        
        $request= $this->getRequest();
        $menu = $request->query->get('menu');
        $accion = $request->query->get('accion');
        $estatus = $request->query->get('estatus');
        
        $form_buscar = $this->createForm(new \INHack20\EquipoBundle\Form\BuscarEquipoType(),null,array('attr' => array('estado' => $this->container->get('security.context')->getToken()->getUser()->getEstado()->getId())));
        //si es una peticion Ajax
        if($request->isXmlHttpRequest())
            {
                $form_buscar->bindRequest($request);
                if($form_buscar->isValid())
                {
                    $data=$form_buscar->getData();
                    //Valido si algun campo tiene datos de busqueda
                    if($data!="" || $data['fecha']!='' || $data['ubicacion']!="" || $data['nrobiennacional'!=''] 
                            || $data['marca']!='' || $data['modelo']!='' || $data['serial']!='')
                    
                    if($data['fecha']!='')
                        $qb->andwhere($qb->expr()->like('a.creado', "'".$data['fecha']->format('Y-m-d')."%'"));
                        
                    if($data['ubicacion']!='')
                        $qb->andWhere('a.ubicacion = :ubicacion')
                                ->setParameter('ubicacion', $data['ubicacion']);
                    
                    if($data['nrobiennacional']!='')
                        $qb->andwhere($qb->expr()->like('a.nroBienNacional', "'".$data['nrobiennacional']."%'"));
                    
                    if($data['marca']!='')
                        $qb->andwhere('e.marca = :marca')
                                ->setParameter('marca', $data['marca']->getMarca());
                    
                    if($data['modelo']!='')
                        $qb->andwhere($qb->expr()->like('e.modelo', "'".$data['modelo']."%'"));
                    
                    if($data['serial']!='')
                        $qb->andwhere($qb->expr()->like('e.serial', "'".$data['serial']."%'"));
                       
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
                            return $this->render('INHack20EquipoBundle:Equipo:lista.html.twig',array(
                                'pager'=> $pager,
                                'comprobante_id'=>$comprobante_id,
                                'comprobante' => $comprobante,
                                'accion' => $accion,
                                'estatus' => $estatus,
                                ));
                        }
                    else
                        return $this->render('INHack20EquipoBundle:Equipo:lista.html.twig', array(
                            'pager'=> $pager,
                            'comprobante_id' => $comprobante_id,
                            'accion' => $accion,
                            'estatus' => $estatus,
                            ));
            }
            
        return array(
            'pager' => $pager,
            'form_buscar' => $form_buscar->createView(),
            'comprobante_id' => $comprobante_id,
            'accion' => $accion,
            'estatus' => $estatus,
        );
    }

    /**
     * Finds and displays a Equipo entity.
     *
     * @Route("/{id}/show", name="equipo_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();
        $accion = $request->query->get('accion');
        $entity = $em->getRepository('INHack20EquipoBundle:Equipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Equipo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'accion' => $accion,
        );
    }

    /**
     * Displays a form to create a new Equipo entity.
     *
     * @Route("/{orden_id}/new", name="equipo_new", requirements={"orden_id"="\d+"}, defaults={"orden_id"="0"})
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function newAction($orden_id)
    {
        $entity = new Equipo();
        
        $form = $this->createForm(new EquipoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'orden_id' => $orden_id,
        );
    }

    /**
     * Creates a new Equipo entity.
     *
     * @Route("/{orden_id}/create", name="equipo_create", requirements={"orden_id"="\d+"}, defaults={"orden_id"="0"})
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("INHack20EquipoBundle:Equipo:new.html.twig")
     */
    public function createAction($orden_id)
    {
        $entity  = new Equipo();
        $request = $this->getRequest();
        $form    = $this->createForm(new EquipoType(), $entity);
        $form->bindRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            
            $activo=$entity->getActivo();
            
            $activo->setTipoActivo($this->container->getParameter('ACTIVO_EQUIPO'));
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
                    return $this->render('INHack20EquipoBundle:Equipo:lista_equipos.html.twig', array('entities' => $orden->getActivos(),'orden_id' => $orden_id,'accion' => $this->container->getParameter('OPERACIONES')));
                }
                else
                    return $this->forward('INHack20EquipoBundle:Equipo:show', array('id' => $entity->getId()),array('accion' => $this->container->getParameter('OPERACIONES')));
             }
             else
                 return $this->redirect ($this->generateUrl('equipo_show',array(
                     'id' => $entity->getId(),
                     'accion' => $this->container->getParameter('OPERACIONES'))));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
             'orden_id' => $orden_id,
        );
    }

    /**
     * Displays a form to edit an existing Equipo entity.
     *
     * @Route("/{orden_id}/{id}/edit", name="equipo_edit", requirements={"orden_id"="\d+"}, defaults={"orden_id"="0"})
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function editAction($orden_id,$id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20EquipoBundle:Equipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Equipo entity.');
        }
        //$entity = new Equipo();
        $editForm = $this->createForm(new EquipoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'orden_id' => $orden_id,
        );
    }

    /**
     * Edits an existing Equipo entity.
     *
     * @Route("/{orden_id}/{id}/update", name="equipo_update", requirements={"orden_id"="\d+"}, defaults={"orden_id"="0"})
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("INHack20EquipoBundle:Equipo:edit.html.twig")
     */
    public function updateAction($orden_id,$id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $entity = $em->getRepository('INHack20EquipoBundle:Equipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Equipo entity.');
        }

        $editForm   = $this->createForm(new EquipoType(), $entity);
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
            $deleteForm = $this->createDeleteForm($id);
            if($request->isXmlHttpRequest()){
                if($orden_id > 0){
                    $orden = $em->getRepository('INHack20InventarioBundle:Orden')->find($orden_id);
                    if (!$orden) {
                        throw $this->createNotFoundException('Unable to find Orden entity.');
                    }
                    return $this->render('INHack20EquipoBundle:Equipo:lista_equipos.html.twig', array('entities' => $orden->getActivos(),'orden_id' => $orden_id));
                }
                else
                    return $this->forward('INHack20EquipoBundle:Equipo:show', array('id' => $id,
                                                                                   'accion' => $this->container->getParameter('OPERACIONES'),
                                                                                    ));
           }
            else
                return $this->redirect($this->generateUrl('equipo_show', array(
                    'id' => $id,
                    'accion' => $this->container->getParameter('OPERACIONES'),
                    )));
        }
        
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Equipo entity.
     *
     * @Route("/{orden_id}/{id}/delete", name="equipo_delete", requirements={"orden_id"="\d+"}, defaults={"orden_id"="0"})
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
            $entity = $em->getRepository('INHack20EquipoBundle:Equipo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Equipo entity.');
            }

            $em->remove($entity);
            $em->flush();
            
            if($orden_id > 0){
                $orden = $em->getRepository('INHack20InventarioBundle:Orden')->find($orden_id);
                if (!$orden) {
                    throw $this->createNotFoundException('Unable to find Orden entity.');
                }
                return $this->render('INHack20EquipoBundle:Equipo:lista_equipos.html.twig', array('entities' => $orden->getActivos(),'orden_id' => $orden_id));
            }
        }
        if($request->isXmlHttpRequest())
            return $this->render('INHack20EquipoBundle:Equipo:index.html.twig');
        else
            return $this->redirect($this->generateUrl('equipo'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
