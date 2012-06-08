<?php

namespace INHack20\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use INHack20\InventarioBundle\Entity\Firma;
use INHack20\InventarioBundle\Form\FirmaType;
use MakerLabs\PagerBundle\Pager;
use MakerLabs\PagerBundle\Adapter\DoctrineOrmAdapter;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Firma controller.
 *
 * @Route("/firma")
 */
class FirmaController extends Controller
{
    /**
     * Lists all Firma entities.
     *
     * @Route("/{page}", name="firma", requirements={"page"="\d+"}, defaults={"page"="1"})
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $qb= $em->getRepository('INHack20InventarioBundle:Firma')->createQueryBuilder('f')
                ->where('f.estado = :est')
                ->setParameter('est', $this->container->get('security.context')->getToken()->getUser()->getEstado())
                ->orderBy('f.actualizado', 'DESC')
                ->setMaxResults(30)
                ;
        //paginador
        $adapter= new DoctrineOrmAdapter($qb);
        $pager= new Pager(
                $adapter,
                array('page' => $page, 'limit' => '30')
                );
        $request= $this->getRequest();
        $menu = $request->query->get('menu');
            if($request->isXmlHttpRequest() && $menu == '')
            {
                return $this->render('INHack20InventarioBundle:Firma:lista.html.twig', array('pager'=> $pager));
            }
        return array(
            'pager' => $pager,
        );
    }

    /**
     * Finds and displays a Firma entity.
     *
     * @Route("/{id}/show", name="firma_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20InventarioBundle:Firma')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Firma entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Firma entity.
     *
     * @Route("/new/{id_comprobante}/{id}", name="firma_new", defaults={"id_comprobante"="0","id"="0"})
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function newAction($id_comprobante,$id)
    {
        $entity = new Firma();
        if($id!=0)
        {
            $em= $this->getDoctrine()->getEntityManager();
            $ubicacion= $em->getRepository('INHack20InventarioBundle:Ubicacion')->find($id);
            if(!$ubicacion)
                throw $this->createNotFoundException('Unable to find Ubicacion entity.');
            $entity->setUbicacion ($ubicacion);
            $form   = $this->createForm(new FirmaType(), $entity,array('attr' => array('disabled' => true,'estado' => $this->container->get('security.context')->getToken()->getUser()->getEstado()->getId())));
        }
        else
            $form   = $this->createForm(new FirmaType(), $entity, array('attr' => array( 'estado' => $this->container->get('security.context')->getToken()->getUser()->getEstado()->getId())));

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'id' => $id,
            'id_comprobante' => $id_comprobante,
        );
    }

    /**
     * Creates a new Firma entity.
     *
     * @Route("/create/{id_comprobante}/{id}", name="firma_create", defaults={"id_comprobante"="0","id"="0"})
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("INHack20InventarioBundle:Firma:new.html.twig")
     */
    public function createAction($id_comprobante,$id)
    {
        $entity  = new Firma();
        $request = $this->getRequest();
        $form    = $this->createForm(new FirmaType(), $entity, array('attr' => array( 'estado' => $this->container->get('security.context')->getToken()->getUser()->getEstado()->getId())));
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em= $this->getDoctrine()->getEntityManager();
            if($id!=0)
                { 
                    $ubicacion= $em->getRepository('INHack20InventarioBundle:Ubicacion')->find($id);
                    if(!$ubicacion)
                        throw $this->createNotFoundException('Unable to find Ubicacion entity.');
                    $entity->setUbicacion ($ubicacion);
                 }
            $entity->setEstado($this->container->get('security.context')->getToken()->getUser()->getEstado());
            $em->persist($entity);
            $em->flush();
            
            if($request->isXmlHttpRequest()){
                    if($id_comprobante!=0)
                        return $this->forward('INHack20InventarioBundle:Comprobante:show',array('id' => $id_comprobante),array('accion' => $this->container->getParameter('OPERACIONES')));
                    else
                        return $this->forward('INHack20InventarioBundle:Firma:show', array('id' => $entity->getId()));
            }
            else
            {
                if($id_comprobante!=0)
                        return $this->redirect($this->generateUrl('comprobante_show', array('id' => $id_comprobante)));
                    else
                        return $this->redirect($this->generateUrl('firma_show', array('id' => $entity->getId())));
            }
            
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'id' => $id,
            'id_comprobante' => $id_comprobante,
        );
    }

    /**
     * Displays a form to edit an existing Firma entity.
     *
     * @Route("/{id}/edit", name="firma_edit")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20InventarioBundle:Firma')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Firma entity.');
        }

        $editForm = $this->createForm(new FirmaType(), $entity, array('attr' => array( 'estado' => $this->container->get('security.context')->getToken()->getUser()->getEstado()->getId())));
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Firma entity.
     *
     * @Route("/{id}/update", name="firma_update")
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("INHack20InventarioBundle:Firma:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20InventarioBundle:Firma')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Firma entity.');
        }

        $editForm   = $this->createForm(new FirmaType(), $entity, array('attr' => array( 'estado' => $this->container->get('security.context')->getToken()->getUser()->getEstado()->getId())));
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $entity->setEstado($this->container->get('security.context')->getToken()->getUser()->getEstado());
            $em->persist($entity);
            $em->flush();
            if($request->isXmlHttpRequest())
                return $this->forward('INHack20InventarioBundle:Firma:show', array('id' => $id));
            else
                return $this->redirect($this->generateUrl('firma_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Firma entity.
     *
     * @Route("/{id}/delete", name="firma_delete")
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
            $entity = $em->getRepository('INHack20InventarioBundle:Firma')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Firma entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
        if($request->isXmlHttpRequest())
            return $this->forward('INHack20InventarioBundle:Firma:index');
        else
            return $this->redirect($this->generateUrl('firma'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
