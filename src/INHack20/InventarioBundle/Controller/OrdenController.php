<?php

namespace INHack20\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use INHack20\InventarioBundle\Entity\Orden;
use INHack20\InventarioBundle\Form\OrdenType;
use MakerLabs\PagerBundle\Pager;
use MakerLabs\PagerBundle\Adapter\DoctrineOrmAdapter;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Orden controller.
 *
 * @Route("/orden")
 */
class OrdenController extends Controller
{
    /**
     * Lists all Orden entities.
     *
     * @Route("/{page}", name="orden", requirements={"page" = "\d+"}, defaults={"page"="1"})
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $usuario = $this->container->get('security.context')->getToken()->getUser();
        $qb= $em->getRepository('INHack20InventarioBundle:Orden')->createQueryBuilder('o')
                ->join('o.estado', 'e')
                ;
        $request = $this->getRequest();
        $menu = $request->query->get('menu');
        $form_buscar = $this->createForm(new \INHack20\InventarioBundle\Form\BuscarOrdenType());
        //si es una peticion Ajax
        if($request->isXmlHttpRequest())
            {
                $form_buscar->bindRequest($request);
                if($form_buscar->isValid())
                {
                    $data=$form_buscar->getData();
                    
                    if($data['fecha']!='')
                            $qb->andwhere($qb->expr()->like('o.creado', "'".$data['fecha']->format('Y-m-d')."%'"));
                    
                    if($data['ordenCompra']!='')               
                        $qb->where('o.ordenCompra = :ordenCompra')
                                ->setParameter('ordenCompra',$data['ordenCompra']);
                    
                    if($data['empresa']!='')
                            $qb->andwhere($qb->expr()->like('o.empresa', "'%".$data['empresa']."%'"));   
                    
                    if($data['factura']!='')               
                        $qb->where('o.factura = :factura')
                                ->setParameter('factura',$data['factura']);
                    
                    if($data['fechaFactura']!='')
                            $qb->andwhere($qb->expr()->like('o.fechaFactura', "'".$data['fechaFactura']->format('Y-m-d')."%'"));
                    
                    if($data['actaRecepcion']!='')
                            $qb->andwhere($qb->expr()->like('o.actaRecepcion', "'%".$data['actaRecepcion']."%'"));
                    
                    if($data['actaRecepcionFecha']!='')
                            $qb->andwhere($qb->expr()->like('o.actaRecepcionFecha', "'".$data['actaRecepcionFecha']->format('Y-m-d')."%'"));
                    
                    if($data['tipoActivo']!='')
                            $qb->andWhere('o.tipoActivo= :tipoActivo')
                                ->setParameter('tipoActivo', $data['tipoActivo']);
                        
                       
                }
            }
        $qb->andWhere('o.estado = :estado')->setParameter('estado', $usuario->getEstado());
        $qb->orderBy('o.actualizado', 'DESC')
                        ->setMaxResults($this->container->getParameter('LIMITE_PAGINACION'))
                        ;
        
        //paginador
        $adapter= new DoctrineOrmAdapter($qb);
        $pager= new Pager(
                $adapter,
                array('page' => $page, 'limit' => $this->container->getParameter('LIMITE_PAGINACION'))
                );
         
        $request= $this->getRequest();
            if($request->isXmlHttpRequest() && $menu == '')
            {
                return $this->render('INHack20InventarioBundle:Orden:lista.html.twig', array('pager'=> $pager));
            }
            
        return array(
            'form_buscar' => $form_buscar->createView (),
            'pager' => $pager,
        );
    }

    /**
     * Finds and displays a Orden entity.
     *
     * @Route("/{id}/show", name="orden_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20InventarioBundle:Orden')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Orden entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Orden entity.
     *
     * @Route("/new", name="orden_new")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Orden();
        $form   = $this->createForm(new OrdenType(), $entity, array('attr' => array('disabled' => false)));

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Orden entity.
     *
     * @Route("/create", name="orden_create")
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("INHack20InventarioBundle:Orden:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Orden();
        $request = $this->getRequest();
        $form    = $this->createForm(new OrdenType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $usuario = $this->container->get('security.context')->getToken()->getUser();
            $entity->setEstado($usuario->getEstado());
            $em->persist($entity);
            $em->flush();
            if($request->isXmlHttpRequest())
                return $this->forward('INHack20InventarioBundle:Orden:show', array('id' => $entity->getId()));
            else
                return $this->redirect($this->generateUrl('orden_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Orden entity.
     *
     * @Route("/{id}/edit", name="orden_edit")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20InventarioBundle:Orden')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Orden entity.');
        }

        $editForm = $this->createForm(new OrdenType(), $entity, array('attr' => array('disabled' => true)));
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Orden entity.
     *
     * @Route("/{id}/update", name="orden_update")
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("INHack20InventarioBundle:Orden:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20InventarioBundle:Orden')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Orden entity.');
        }

        $editForm   = $this->createForm(new OrdenType(), $entity, array('attr' => array('disabled' => true)));
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            
            if($request->isXmlHttpRequest())
                return $this->forward('INHack20InventarioBundle:Orden:show', array('id' => $id));
            else
                return $this->redirect($this->generateUrl('orden_show',array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Orden entity.
     *
     * @Route("/{id}/delete", name="orden_delete")
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('INHack20InventarioBundle:Orden')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Orden entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
        if($request->isXmlHttpRequest())
            return $this->forward('INHack20InventarioBundle:Orden:index');
        else
            return $this->redirect($this->generateUrl('orden'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
