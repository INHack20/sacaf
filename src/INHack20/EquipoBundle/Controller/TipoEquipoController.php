<?php

namespace INHack20\EquipoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use INHack20\EquipoBundle\Entity\TipoEquipo;
use INHack20\EquipoBundle\Form\TipoEquipoType;

/**
 * TipoEquipo controller.
 *
 * @Route("/tipoequipo")
 */
class TipoEquipoController extends Controller
{
    /**
     * Lists all TipoEquipo entities.
     *
     * @Route("/", name="tipoequipo")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('INHack20EquipoBundle:TipoEquipo')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a TipoEquipo entity.
     *
     * @Route("/{id}/show", name="tipoequipo_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20EquipoBundle:TipoEquipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoEquipo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new TipoEquipo entity.
     *
     * @Route("/new", name="tipoequipo_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TipoEquipo();
        $form   = $this->createForm(new TipoEquipoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new TipoEquipo entity.
     *
     * @Route("/create", name="tipoequipo_create")
     * @Method("post")
     * @Template("INHack20EquipoBundle:TipoEquipo:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new TipoEquipo();
        $request = $this->getRequest();
        $form    = $this->createForm(new TipoEquipoType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
            if($request->isXmlHttpRequest())
                return $this->forward('INHack20EquipoBundle:TipoEquipo:show',array('id' => $entity->getId()));
            else
                return $this->redirect($this->generateUrl('tipoequipo_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TipoEquipo entity.
     *
     * @Route("/{id}/edit", name="tipoequipo_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20EquipoBundle:TipoEquipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoEquipo entity.');
        }

        $editForm = $this->createForm(new TipoEquipoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing TipoEquipo entity.
     *
     * @Route("/{id}/update", name="tipoequipo_update")
     * @Method("post")
     * @Template("INHack20EquipoBundle:TipoEquipo:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20EquipoBundle:TipoEquipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoEquipo entity.');
        }

        $editForm   = $this->createForm(new TipoEquipoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            if($request->isXmlHttpRequest())
                return $this->forward('INHack20EquipoBundle:TipoEquipo:show',array('id' => $entity->getId()));
            else
                return $this->redirect($this->generateUrl('tipoequipo_show', array('id' => $entity->getId())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a TipoEquipo entity.
     *
     * @Route("/{id}/delete", name="tipoequipo_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('INHack20EquipoBundle:TipoEquipo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TipoEquipo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
        
        if($request->isXmlHttpRequest())
            return $this->forward('INHack20EquipoBundle:TipoEquipo:index');
        else
            return $this->redirect($this->generateUrl('tipoequipo'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
