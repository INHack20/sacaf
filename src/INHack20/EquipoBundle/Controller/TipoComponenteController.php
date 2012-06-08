<?php

namespace INHack20\EquipoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use INHack20\EquipoBundle\Entity\TipoComponente;
use INHack20\EquipoBundle\Form\TipoComponenteType;

/**
 * TipoComponente controller.
 *
 * @Route("/tipocomponente")
 */
class TipoComponenteController extends Controller
{
    /**
     * Lists all TipoComponente entities.
     *
     * @Route("/", name="tipocomponente")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('INHack20EquipoBundle:TipoComponente')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a TipoComponente entity.
     *
     * @Route("/{id}/show", name="tipocomponente_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20EquipoBundle:TipoComponente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoComponente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new TipoComponente entity.
     *
     * @Route("/new", name="tipocomponente_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TipoComponente();
        $form   = $this->createForm(new TipoComponenteType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new TipoComponente entity.
     *
     * @Route("/create", name="tipocomponente_create")
     * @Method("post")
     * @Template("INHack20EquipoBundle:TipoComponente:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new TipoComponente();
        $request = $this->getRequest();
        $form    = $this->createForm(new TipoComponenteType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
            if($request->isXmlHttpRequest())
                return $this->forward('INHack20EquipoBundle:TipoComponente:show', array('id' => $entity->getId()));
            else
                return $this->redirect($this->generateUrl('tipocomponente_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TipoComponente entity.
     *
     * @Route("/{id}/edit", name="tipocomponente_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20EquipoBundle:TipoComponente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoComponente entity.');
        }

        $editForm = $this->createForm(new TipoComponenteType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing TipoComponente entity.
     *
     * @Route("/{id}/update", name="tipocomponente_update")
     * @Method("post")
     * @Template("INHack20EquipoBundle:TipoComponente:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20EquipoBundle:TipoComponente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoComponente entity.');
        }

        $editForm   = $this->createForm(new TipoComponenteType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            if($request->isXmlHttpRequest())
                return $this->forward('INHack20EquipoBundle:TipoComponente:show',array('id' => $entity->getId()));
            else
                return $this->redirect($this->generateUrl('tipocomponente_show', array('id' => $entity->getId())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a TipoComponente entity.
     *
     * @Route("/{id}/delete", name="tipocomponente_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('INHack20EquipoBundle:TipoComponente')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TipoComponente entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
        
        if($request->isXmlHttpRequest())
                return $this->forward('INHack20EquipoBundle:TipoComponente:index');
            else
                return $this->redirect($this->generateUrl('tipocomponente'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
