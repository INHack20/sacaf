<?php

namespace INHack20\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use INHack20\InventarioBundle\Entity\Activo;
use INHack20\InventarioBundle\Form\ActivoType;
use INHack20\InventarioBundle\Extras\ReportesPDF\Activo\ReporteInventario;

/**
 * Activo controller.
 *
 * @Route("/activo")
 */
class ActivoController extends Controller
{
    /**
     * Lists all Activo entities.
     *
     * @Route("/", name="activo")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('INHack20InventarioBundle:Activo')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Activo entity.
     *
     * @Route("/{id}/show", name="activo_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20InventarioBundle:Activo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Activo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Activo entity.
     *
     * @Route("/new", name="activo_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Activo();
        $form   = $this->createForm(new ActivoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Activo entity.
     *
     * @Route("/create", name="activo_create")
     * @Method("post")
     * @Template("INHack20InventarioBundle:Activo:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Activo();
        $request = $this->getRequest();
        $form    = $this->createForm(new ActivoType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('activo_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Activo entity.
     *
     * @Route("/{id}/edit", name="activo_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20InventarioBundle:Activo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Activo entity.');
        }

        $editForm = $this->createForm(new ActivoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Activo entity.
     *
     * @Route("/{id}/update", name="activo_update")
     * @Method("post")
     * @Template("INHack20InventarioBundle:Activo:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20InventarioBundle:Activo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Activo entity.');
        }

        $editForm   = $this->createForm(new ActivoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('activo_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Activo entity.
     *
     * @Route("/{id}/delete", name="activo_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('INHack20InventarioBundle:Activo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Activo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('activo'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * Devuelve un reporte de los activos solicitados, por tipo, dependencia y estados 
     * @Route("/inventario", name="activo_inventario")
     * @Template()
     */
    public function inventarioAction(){
        $request = $this->getRequest();
        $tipoActivo = $request->query->get('tipoActivo');
        $form = $this->createFormInventario();
        return array(
            'form' => $form->createView(),
            'tipoActivo' => $tipoActivo
        );
    }
    
    /**
     * Devuelve un reporte de los activos solicitados, por tipo, dependencia y estados 
     * @Route("/reporte", name="activo_reporte")
     */
    public function reporteAction(){
        $request = $this->getRequest();
        $tipoActivo = $request->query->get('tipoActivo');
        $estatus = $request->query->get('estatus');
        
        $form = $this->createFormInventario();
        
        $em= $this->getDoctrine()->getEntityManager();
        $qb = $em->getRepository('INHack20InventarioBundle:Activo')->createQueryBuilder('a');
        
        if($tipoActivo != '')
            $qb->andwhere('a.tipoactivo = :tipoActivo')->setParameter('tipoActivo', $tipoActivo);
        if($estatus != '')
            $qb->andwhere('a.estatus = :estatus')->setParameter('estatus', $estatus);
        
        $ubicacion= NULL;
            if($request->getMethod()=='POST')
            {
                $form->bindRequest($request);
                if($form->isValid())
                {
                    $data=$form->getData();

                    $ubicacion= $em->getRepository('INHack20InventarioBundle:Ubicacion')->find($data['dependencia']->getId());
                    if(!$ubicacion)
                    {
                        throw $this->createNotFoundException('No se ha podido encontrar la entidad Ubicacion');
                    }

                    $qb->andWhere('a.estatus != :estatus1')->setParameter('estatus1', $this->container->getParameter('STOCK_ALMACEN'));

                    $qb->andWhere('a.estatus != :estatus2')->setParameter('estatus2', $this->container->getParameter('DESINCORPORADO'));

                    $qb->andWhere('a.ubicacion = :ubicacion')->setParameter('ubicacion', $ubicacion);
                }
            }
        $activos = $qb->getQuery()->getResult();
        
        new ReporteInventario($tipoActivo,$ubicacion,$activos,$this->container,$this->container->get('security.context')->getToken()->getUser(),$estatus,$this->container->get('security.context')->getToken()->getUser());
        return new Response('', 200, array('Content-Type' => 'application/pdf'));
    }
    
    private function createFormInventario(){
        $estado = $this->container->get('security.context')->getToken()->getUser()->getEstado();
        return $this->createFormBuilder()
                ->add('dependencia','entity',array(
                    'class' => 'INHack20\InventarioBundle\Entity\Ubicacion', 
                    'property' => 'dependencia',
                    'label'=> 'Dependencia',
                    'empty_value' => 'Seleccione',
                    'attr' => array('style' => 'width:400px;font-size:9px'),
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
    
}
